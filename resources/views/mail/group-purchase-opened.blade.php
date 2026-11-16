<x-mail::message>
# {{ $purchase->title }}

@if ($purchase->contract_offer_id)
Les commandes sont ouvertes chez **{{ $purchase->producer->name }}**. Vous êtes engagé·e auprès de ce producteur : votre commande sera déduite de votre engagement, vous n'avez rien à régler.
@else
Une nouvelle commande groupée est ouverte avec **{{ $purchase->producer->name }}**.
@endif

{{ $purchase->description }}

- **Commandes jusqu'au :** {{ $purchase->closes_at->translatedFormat('l j F à H\hi') }}
- **Livraison :** {{ $purchase->distribution->label() }}

<x-mail::button :url="route('espace.commandes-groupees.show', $purchase)" color="success">
Je commande
</x-mail::button>

{{ config('amap.name') }}
</x-mail::message>
