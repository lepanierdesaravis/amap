@php
    use App\Enums\Frequency;
    $distributions = $this->distributions;
    $byId = $distributions->keyBy('id');
    $selected = $this->selectedDates;
    $editable = $this->editable;
    $producer = $offer->producer;
@endphp
<div>
    <x-member-nav />

    <div class="mx-auto max-w-6xl px-4 py-8">
        <a href="{{ route('espace.contrats.index') }}" class="text-sm font-bold text-doux no-underline hover:text-brun">← Mes contrats</a>

        <div class="mt-3 flex flex-wrap items-end gap-x-6 gap-y-2">
            <div>
                <p class="kicker">{{ $offer->title }}</p>
                <h1 class="text-3xl font-extrabold">{{ $producer->name }}</h1>
                <p class="mt-1 text-doux">
                    Du {{ $offer->starts_on->translatedFormat('j F') }} au {{ $offer->ends_on->translatedFormat('j F Y') }}
                    · {{ $distributions->count() }} distributions
                </p>
            </div>
            <div class="ml-auto text-sm">
                @if ($offer->isOpen())
                    <span class="badge bg-vert-clair px-3 py-1 text-sm text-vert-fonce">Souscriptions ouvertes jusqu'au {{ $offer->closes_at->translatedFormat('j F à H\hi') }}</span>
                @else
                    <span class="badge bg-brun/10 px-3 py-1 text-sm text-brun">Souscriptions {{ $offer->status()->getLabel() === 'À venir' ? 'à partir du '.$offer->opens_at->translatedFormat('j F') : 'clôturées' }}</span>
                @endif
            </div>
        </div>

        @unless (auth()->user()->canOrder())
            <div class="mt-6 rounded-2xl bg-miel-clair p-4 ring-1 ring-miel/40">
                <p class="font-bold">Votre adhésion n'est pas encore validée.</p>
                <p class="text-sm text-doux">Vous pourrez souscrire dès que la cotisation sera enregistrée. <a href="{{ route('adherer') }}" class="font-bold text-vert">Comment adhérer ?</a></p>
            </div>
        @endunless

        @if ($this->remainingPlaces !== null)
            @if ($this->remainingPlaces === 0 && ! $saved)
                <div class="mt-6 rounded-2xl bg-terre-clair p-4 ring-1 ring-terre/30">
                    <p class="font-bold">Ce contrat est complet.</p>
                    <p class="text-sm text-doux">Le nombre de places est limité par le producteur. Contactez le référent si une place se libère.</p>
                </div>
            @elseif (! $saved)
                <div class="mt-6 rounded-2xl bg-miel-clair p-4 text-sm ring-1 ring-miel/40">
                    ⏳ Places limitées : <strong>plus que {{ $this->remainingPlaces }} place{{ $this->remainingPlaces > 1 ? 's' : '' }}</strong>. Premier arrivé, premier servi.
                </div>
            @endif
        @endif

        @if ($saved && $editable)
            <div class="mt-6 rounded-2xl bg-vert-clair p-4 text-sm ring-1 ring-vert/30">
                ✓ Vous avez déjà un contrat avec {{ $producer->name }}. Vous pouvez le modifier jusqu'au {{ $offer->closes_at->translatedFormat('j F') }}.
            </div>
        @endif

        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_380px]">
            {{-- Produits --}}
            <section aria-labelledby="titre-produits">
                <h2 id="titre-produits" class="text-xl font-bold">1. Je choisis mes produits</h2>
                <div class="mt-4 space-y-4">
                    @foreach ($offer->products as $product)
                        @php($line = $lines[$product->id])
                        @php($dates = $selected[$product->id])
                        <article wire:key="product-{{ $product->id }}" @class(['card transition', 'ring-2 ring-vert' => $line['quantity'] > 0])>
                            <div class="flex items-start gap-4">
                                @if ($product->photo)
                                    <img src="{{ $product->photoUrl() }}" alt="" class="size-14 shrink-0 rounded-full object-cover">
                                @else
                                    {!! $product->iconBadge('3.5rem') !!}
                                @endif
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                                    <p class="text-sm text-doux">{{ $product->is_amount ? $this->money($product->price_cents).' pour la saison' : $this->money($product->price_cents).' par '.$product->unit }}@if ($product->description) · {{ $product->description }}@endif</p>
                                </div>
                                @if ($product->is_amount)
                                    <button type="button" wire:click="chooseAmount({{ $product->id }})" @disabled(! $editable) aria-pressed="{{ $line['quantity'] > 0 ? 'true' : 'false' }}"
                                            @class(['rounded-xl px-4 py-2 text-sm font-bold ring-1 disabled:opacity-40',
                                                    'bg-vert text-white ring-vert' => $line['quantity'] > 0,
                                                    'bg-white ring-brun/20 hover:bg-vert-clair' => $line['quantity'] === 0])>
                                        {{ $line['quantity'] > 0 ? '✓ Choisi' : 'Choisir' }}
                                    </button>
                                @else
                                <div class="flex items-center gap-2" role="group" aria-label="Quantité de {{ $product->name }}">
                                    <button type="button" wire:click="decrement({{ $product->id }})" @disabled(! $editable || $line['quantity'] === 0) class="size-10 rounded-xl bg-creme text-xl font-bold ring-1 ring-brun/20 hover:bg-vert-clair disabled:opacity-40" aria-label="Moins">−</button>
                                    <span class="w-6 text-center text-lg font-extrabold" aria-live="polite">{{ $line['quantity'] }}</span>
                                    <button type="button" wire:click="increment({{ $product->id }})" @disabled(! $editable) class="size-10 rounded-xl bg-vert text-xl font-bold text-white hover:bg-vert-fonce disabled:opacity-40" aria-label="Plus">+</button>
                                </div>
                                @endif
                            </div>

                            @if ($line['quantity'] > 0 && $product->is_amount && $offer->allowedFrequencies() === [])
                                <p class="mt-4 border-t border-brun/10 pt-4 text-sm">
                                    Engagement de <strong class="text-vert">{{ $this->money($product->price_cents) }}</strong> pour la saison.
                                    @if ($offer->groupPurchases()->exists())
                                        Vous commanderez vos produits au fil de la saison ; le producteur les déduit de ce montant.
                                    @endif
                                </p>
                            @elseif ($line['quantity'] > 0)
                                <div class="mt-4 border-t border-brun/10 pt-4">
                                    <p class="label">{{ $product->is_amount ? 'Quand est-ce que je viens chercher mes produits ?' : 'À quel rythme ?' }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($offer->allowedFrequencies() as $frequency)
                                            <label @class(['cursor-pointer rounded-xl px-3 py-2 text-sm font-bold ring-1 transition',
                                                           'bg-vert text-white ring-vert' => $line['frequency'] === $frequency->value,
                                                           'bg-white ring-brun/20 hover:bg-vert-clair' => $line['frequency'] !== $frequency->value])>
                                                <input type="radio" class="sr-only" wire:model.live="lines.{{ $product->id }}.frequency" value="{{ $frequency->value }}" @disabled(! $editable)>
                                                {{ $frequency->getLabel() }}
                                            </label>
                                        @endforeach
                                    </div>

                                    @if ($line['frequency'] === Frequency::Alternate->value && $distributions->count() > 1)
                                        <p class="label mt-4">Première livraison</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ([0, 1] as $start)
                                                <label @class(['cursor-pointer rounded-xl px-3 py-2 text-sm font-bold ring-1',
                                                               'bg-terre-clair text-terre-fonce ring-terre' => (int) $line['start'] === $start,
                                                               'bg-white ring-brun/20' => (int) $line['start'] !== $start])>
                                                    <input type="radio" class="sr-only" wire:model.live="lines.{{ $product->id }}.start" value="{{ $start }}" @disabled(! $editable)>
                                                    le {{ $distributions[$start]->date->translatedFormat('j F') }}
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if ($line['frequency'] === Frequency::Custom->value)
                                        <p class="label mt-4">Touchez les dates qui vous conviennent</p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($distributions as $distribution)
                                                @php($on = in_array($distribution->id, $dates, true))
                                                <button type="button" wire:click="toggleDate({{ $product->id }}, {{ $distribution->id }})" @disabled(! $editable)
                                                        aria-pressed="{{ $on ? 'true' : 'false' }}"
                                                        @class(['rounded-lg px-2.5 py-1.5 text-xs font-bold ring-1',
                                                                'bg-vert text-white ring-vert' => $on,
                                                                'bg-white text-doux ring-brun/20 hover:bg-vert-clair' => ! $on])>
                                                    {{ $distribution->date->translatedFormat('j M') }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif

                                    <p class="mt-4 text-sm">
                                        @if ($product->is_amount)
                                            <strong>{{ count($dates) }} livraison{{ count($dates) > 1 ? 's' : '' }}</strong>
                                            pour un engagement de <strong class="text-vert">{{ $this->money($this->lineTotal($product->id)) }}</strong>
                                        @else
                                            <strong>{{ count($dates) }} livraison{{ count($dates) > 1 ? 's' : '' }}</strong>
                                            × {{ $line['quantity'] }} × {{ $this->money($product->price_cents) }}
                                            = <strong class="text-vert">{{ $this->money($this->lineTotal($product->id)) }}</strong>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>

                @if ($offer->terms)
                    <details class="card mt-6">
                        <summary class="cursor-pointer font-bold">Lire le contrat d'engagement</summary>
                        <div class="prose-amap mt-4 text-sm">{!! $offer->terms !!}</div>
                    </details>
                @endif
            </section>

            {{-- Calendrier et panier --}}
            <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                <section class="card" aria-labelledby="titre-calendrier">
                    <h2 id="titre-calendrier" class="text-xl font-bold">2. Mon calendrier</h2>
                    <p class="mt-1 text-sm text-doux">Vos livraisons apparaissent ici au fur et à mesure.</p>
                    <x-calendrier-saison :months="$this->months" :legend="$this->legend" compact class="mt-4 max-h-[30rem] overflow-y-auto pr-1 pb-24" />
                    <p class="mt-3 text-xs text-doux">Les grandes icônes sont ce contrat ; les petites, ce que vous avez déjà commandé ailleurs. Survolez ou touchez une date pour le détail.</p>
                </section>

                <section class="card" aria-labelledby="titre-panier">
                    <h2 id="titre-panier" class="text-xl font-bold">3. Mon panier</h2>
                    <div class="mt-3 flex items-baseline justify-between border-b border-brun/10 pb-3">
                        <span class="text-doux">Total pour la saison</span>
                        <span class="text-2xl font-extrabold text-vert">{{ $this->money($this->totalCents) }}</span>
                    </div>

                    @if ($this->totalCents > 0)
                        <p class="label mt-4">Je règle en</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($offer->paymentPlans() as $plan)
                                <label @class(['cursor-pointer rounded-xl px-3 py-2 text-sm font-bold ring-1',
                                               'bg-vert text-white ring-vert' => $installments === $plan['installments'],
                                               'bg-white ring-brun/20' => $installments !== $plan['installments']])>
                                    <input type="radio" class="sr-only" wire:model.live="installments" value="{{ $plan['installments'] }}" @disabled(! $editable)>
                                    {{ $plan['installments'] }} fois
                                </label>
                            @endforeach
                        </div>

                        <p class="label mt-4">Par</p>
                        <div class="flex gap-2">
                            @foreach (['cheque' => 'Chèque', 'virement' => 'Virement'] as $value => $label)
                                <label @class(['cursor-pointer rounded-xl px-3 py-2 text-sm font-bold ring-1',
                                               'bg-vert text-white ring-vert' => $paymentMethod === $value,
                                               'bg-white ring-brun/20' => $paymentMethod !== $value])>
                                    <input type="radio" class="sr-only" wire:model.live="paymentMethod" value="{{ $value }}" @disabled(! $editable)>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>

                        <ul class="mt-4 space-y-1 rounded-xl bg-creme p-3 text-sm">
                            @foreach ($this->schedule as $i)
                                <li class="flex justify-between"><span>{{ ucfirst($i['due_on']->translatedFormat('F Y')) }}</span><strong>{{ $this->money($i['amount_cents']) }}</strong></li>
                            @endforeach
                        </ul>
                        <p class="mt-2 text-xs text-doux">
                            @if ($paymentMethod === 'cheque' && $producer->cheque_payee)
                                Chèques à l'ordre de <strong>{{ $producer->cheque_payee }}</strong>, remis à votre référent.
                            @elseif ($paymentMethod === 'virement')
                                Virements directement au producteur ; ses coordonnées bancaires figurent dans l'e-mail de confirmation.
                            @endif
                            L'AMAP n'encaisse rien.
                        </p>
                    @endif

                    @error('save')
                        <p class="mt-4 rounded-xl bg-terre-clair p-3 text-sm font-bold text-tomate" role="alert">{{ $message }}</p>
                    @enderror

                    @if ($editable)
                        <button type="button" wire:click="save" wire:loading.attr="disabled" class="btn-primary mt-5 w-full py-3 text-lg">
                            <span wire:loading.remove wire:target="save">{{ $saved ? 'Enregistrer les modifications' : 'Valider mon contrat' }}</span>
                            <span wire:loading wire:target="save">Enregistrement…</span>
                        </button>
                    @endif
                </section>
            </aside>
        </div>
    </div>
</div>
