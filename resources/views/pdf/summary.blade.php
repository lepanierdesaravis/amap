<!doctype html>
<html lang="fr"><head><meta charset="utf-8">@include('pdf._style')</head>
<body>
<footer>{{ config('amap.name') }} — récapitulatif généré le {{ now()->translatedFormat('j F Y à H\hi') }}</footer>
<header>
    <img src="{{ public_path('images/logo-600.png') }}" alt="">
    <h1>{{ $producer->name }}</h1>
    <div class="meta">Distribution n°{{ $distribution->number() }} — {{ $distribution->label() }}, {{ $distribution->timeRange() }}<br>{{ $distribution->place }}</div>
</header>

<h2>À préparer</h2>
<table>
    <tr><th>Produit</th><th class="qty">Total</th></tr>
    @forelse ($totals as $label => $quantity)
        <tr><td>{{ $label }}</td><td class="qty"><strong>{{ $quantity }}</strong></td></tr>
    @empty
        <tr><td colspan="2">Aucune commande pour cette distribution.</td></tr>
    @endforelse
</table>

@if ($members->isNotEmpty())
<h2>Détail par adhérent ({{ $members->count() }})</h2>
<table>
    <tr><th>Adhérent</th><th>Produits</th></tr>
    @foreach ($members as $member)
        <tr>
            <td>{{ $member['user']->sort_name }}</td>
            <td>@foreach ($member['lines'] as $label => $quantity){{ $quantity }} × {{ $label }}@unless($loop->last)<br>@endunless @endforeach</td>
        </tr>
    @endforeach
</table>
@endif
</body></html>
