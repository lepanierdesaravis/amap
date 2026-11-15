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
                                <div class="text-3xl" aria-hidden="true">{{ $product->icon ?: '🧺' }}</div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                                    <p class="text-sm text-doux">{{ $this->money($product->price_cents) }} par {{ $product->unit }}@if ($product->description) · {{ $product->description }}@endif</p>
                                </div>
                                <div class="flex items-center gap-2" role="group" aria-label="Quantité de {{ $product->name }}">
                                    <button type="button" wire:click="decrement({{ $product->id }})" @disabled(! $editable || $line['quantity'] === 0) class="size-10 rounded-xl bg-creme text-xl font-bold ring-1 ring-brun/20 hover:bg-vert-clair disabled:opacity-40" aria-label="Moins">−</button>
                                    <span class="w-6 text-center text-lg font-extrabold" aria-live="polite">{{ $line['quantity'] }}</span>
                                    <button type="button" wire:click="increment({{ $product->id }})" @disabled(! $editable) class="size-10 rounded-xl bg-vert text-xl font-bold text-white hover:bg-vert-fonce disabled:opacity-40" aria-label="Plus">+</button>
                                </div>
                            </div>

                            @if ($line['quantity'] > 0)
                                <div class="mt-4 border-t border-brun/10 pt-4">
                                    <p class="label">À quel rythme ?</p>
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
                                        <strong>{{ count($dates) }} livraison{{ count($dates) > 1 ? 's' : '' }}</strong>
                                        × {{ $line['quantity'] }} × {{ $this->money($product->price_cents) }}
                                        = <strong class="text-vert">{{ $this->money($product->price_cents * $line['quantity'] * count($dates)) }}</strong>
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
                    <div class="mt-4 max-h-[26rem] space-y-5 overflow-y-auto pr-1">
                        @foreach ($this->months as $month)
                            <div>
                                <p class="mb-1.5 text-sm font-bold">{{ $month['label'] }}</p>
                                <div class="grid grid-cols-7 gap-1 text-center text-[11px]">
                                    @foreach (['L', 'M', 'M', 'J', 'V', 'S', 'D'] as $d)<div class="text-doux/70">{{ $d }}</div>@endforeach
                                    @foreach ($month['cells'] as $cell)
                                        @if ($cell === null)
                                            <div></div>
                                        @elseif ($cell['distribution'])
                                            <div @class(['flex h-11 flex-col items-center justify-start rounded-lg pt-0.5 font-bold ring-1',
                                                         'bg-vert-clair ring-vert' => $cell['icons'],
                                                         'bg-white text-doux ring-sauge/60' => ! $cell['icons']])
                                                 title="Distribution du {{ $cell['day'] }}">
                                                {{ $cell['day'] }}
                                                <span class="text-sm leading-none">{{ implode('', $cell['icons']) }}</span>
                                            </div>
                                        @else
                                            <div class="h-11 pt-0.5 text-doux/40">{{ $cell['day'] }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-3 flex items-center gap-2 text-xs text-doux">
                        <span class="inline-block size-3 rounded bg-white ring-1 ring-sauge"></span> date de distribution
                        <span class="ml-2 inline-block size-3 rounded bg-vert-clair ring-1 ring-vert"></span> je récupère un panier
                    </p>
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
