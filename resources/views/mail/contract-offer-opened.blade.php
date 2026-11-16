<x-mail::message>
# {{ $offer->producer->name }}

Les souscriptions au contrat **{{ $offer->title }}** sont ouvertes.

- **Période :** du {{ $offer->starts_on->translatedFormat('j F Y') }} au {{ $offer->ends_on->translatedFormat('j F Y') }}
- **Souscriptions jusqu'au :** {{ $offer->closes_at->translatedFormat('l j F à H\hi') }}
@if ($offer->max_contracts)
- **Places limitées :** {{ $offer->max_contracts }} contrats, premier arrivé, premier servi.
@endif

<x-mail::button :url="route('espace.contrats.show', $offer)" color="success">
Je souscris
</x-mail::button>

{{ config('amap.name') }}
</x-mail::message>
