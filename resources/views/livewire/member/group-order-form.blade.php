@php($editable = $this->editable)
<div>
    <x-member-nav />

    <div class="mx-auto max-w-4xl px-4 py-8">
        <a href="{{ route('espace.commandes-groupees.index') }}" class="text-sm font-bold text-doux no-underline hover:text-brun">← Commandes groupées</a>

        <div class="mt-3 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-brun/10">
            @if ($purchase->photo)
                <img src="{{ Storage::disk('public')->url($purchase->photo) }}" alt="" class="h-44 w-full object-cover">
            @endif
            <div class="p-6">
                <p class="kicker">{{ $purchase->producer->name }}</p>
                <h1 class="text-3xl font-extrabold">{{ $purchase->title }}</h1>
                @if ($purchase->description)<p class="mt-2 text-doux">{{ $purchase->description }}</p>@endif

                <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                    <div class="rounded-xl bg-creme p-3">
                        <dt class="text-doux">Commandes jusqu'au</dt>
                        <dd class="font-bold">{{ $purchase->closes_at->translatedFormat('l j F à H\hi') }}</dd>
                    </div>
                    <div class="rounded-xl bg-creme p-3">
                        <dt class="text-doux">Je récupère ma commande</dt>
                        <dd class="font-bold">{{ $purchase->distribution->label() }}, {{ $purchase->distribution->timeRange() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @unless ($purchase->isOpen())
            <p class="mt-6 rounded-2xl bg-brun/5 p-4 font-bold">Cette commande groupée est {{ $purchase->status()->getLabel() === 'À venir' ? 'ouverte à partir du '.$purchase->opens_at->translatedFormat('j F à H\hi') : 'clôturée' }}.</p>
        @endunless
        @unless (auth()->user()->canOrder())
            <p class="mt-6 rounded-2xl bg-miel-clair p-4 ring-1 ring-miel/40"><strong>Votre adhésion n'est pas encore validée.</strong> Vous pourrez commander dès que la cotisation sera enregistrée.</p>
        @endunless

        <section class="mt-6 space-y-3" aria-label="Articles">
            @foreach ($purchase->items as $item)
                @php($q = (int) ($quantities[$item->id] ?? 0))
                <div wire:key="item-{{ $item->id }}" @class(['card flex items-center gap-4 py-4', 'ring-2 ring-vert' => $q > 0])>
                    <div class="flex-1">
                        <p class="font-bold">{{ $item->name }}</p>
                        <p class="text-sm text-doux">
                            {{ $item->packaging ? $item->packaging.' · ' : '' }}{{ $this->money($item->price_cents) }}
                            @if ($item->max_per_member) · {{ $item->max_per_member }} maximum @endif
                        </p>
                    </div>
                    @if ($q > 0)<span class="hidden text-sm font-bold text-vert sm:block">{{ $this->money($q * $item->price_cents) }}</span>@endif
                    <div class="flex items-center gap-2" role="group" aria-label="Quantité de {{ $item->name }}">
                        <button type="button" wire:click="decrement({{ $item->id }})" @disabled(! $editable || $q === 0) class="size-10 rounded-xl bg-creme text-xl font-bold ring-1 ring-brun/20 disabled:opacity-40" aria-label="Moins">−</button>
                        <span class="w-6 text-center text-lg font-extrabold" aria-live="polite">{{ $q }}</span>
                        <button type="button" wire:click="increment({{ $item->id }})" @disabled(! $editable || ($item->max_per_member && $q >= $item->max_per_member)) class="size-10 rounded-xl bg-vert text-xl font-bold text-white disabled:opacity-40" aria-label="Plus">+</button>
                    </div>
                </div>
            @endforeach
        </section>

        <div class="card mt-6 flex flex-wrap items-center gap-4">
            <div>
                <p class="text-doux">Total</p>
                <p class="text-2xl font-extrabold text-vert">{{ $this->money($this->totalCents) }}</p>
            </div>
            @if ($this->contract)
                <p class="max-w-md text-sm text-doux">
                    ✓ Déduit de votre engagement : rien à régler.<br>
                    Solde restant estimé après cette commande : <strong @class(['text-brun' => $this->remainingAfter >= 0, 'text-tomate' => $this->remainingAfter < 0])>{{ $this->money($this->remainingAfter) }}</strong>
                </p>
            @elseif ($purchase->contract_offer_id)
                <p class="max-w-md text-sm text-doux">💶 Commande ponctuelle : à régler sur place à {{ $purchase->producer->name }}, lors de la distribution du {{ $purchase->distribution->date->translatedFormat('j F') }}.@if ($purchase->payment_info) {{ $purchase->payment_info }}@endif</p>
            @elseif ($purchase->payment_info)
                <p class="max-w-md text-sm text-doux">💶 {{ $purchase->payment_info }}</p>
            @endif
            @if ($editable)
                <button type="button" wire:click="save" wire:loading.attr="disabled" class="btn-primary ml-auto py-3 text-lg">
                    {{ $hasOrder ? ($this->totalCents > 0 ? 'Enregistrer les modifications' : 'Annuler ma commande') : 'Valider ma commande' }}
                </button>
            @endif
            @error('save')<p class="w-full rounded-xl bg-terre-clair p-3 text-sm font-bold text-tomate" role="alert">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
