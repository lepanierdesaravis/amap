<x-mail::message>
# Bonjour,

Voici le récapitulatif des commandes de **{{ $producer->name }}** pour la distribution du **{{ $distribution->label() }}** ({{ $distribution->timeRange() }}, {{ $distribution->place }}).

Le détail par produit et par adhérent est en pièce jointe. Il est aussi disponible dans votre espace producteur.

<x-mail::button :url="route('producteur.index')" color="success">
Mon espace producteur
</x-mail::button>

Merci et à samedi,<br>
{{ config('amap.name') }}
</x-mail::message>
