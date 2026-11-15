<x-mail::message>
# {{ $purchase->title }}

Une nouvelle commande groupée est ouverte avec **{{ $purchase->producer->name }}**.

{{ $purchase->description }}

- **Commandes jusqu'au :** {{ $purchase->closes_at->translatedFormat('l j F à H\hi') }}
- **Livraison :** {{ $purchase->distribution->label() }}

<x-mail::button :url="route('espace.commandes-groupees.show', $purchase)" color="success">
Je commande
</x-mail::button>

{{ config('amap.name') }}
</x-mail::message>
