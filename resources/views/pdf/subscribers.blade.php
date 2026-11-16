<!doctype html>
<html lang="fr"><head><meta charset="utf-8">@include('pdf._style')</head>
<body>
<footer>{{ config('amap.name') }} — liste générée le {{ now()->translatedFormat('j F Y à H\hi') }}</footer>
<header>
    <img src="{{ public_path('images/logo-600.png') }}" alt="">
    <h1>{{ $offer->producer->name }}</h1>
    <div class="meta">{{ $offer->title }} — du {{ $offer->starts_on->translatedFormat('j F Y') }} au {{ $offer->ends_on->translatedFormat('j F Y') }}</div>
</header>

<h2>Adhérents engagés ({{ $rows->count() }})</h2>
<table>
    <tr><th>Nom</th><th>Prénom</th><th>E-mail</th><th>Engagement</th></tr>
    @forelse ($rows as $row)
        <tr><td>{{ $row['last_name'] }}</td><td>{{ $row['first_name'] }}</td><td>{{ $row['email'] }}</td><td>{{ $row['engagement'] }}</td></tr>
    @empty
        <tr><td colspan="4">Aucun contrat pour le moment.</td></tr>
    @endforelse
</table>
</body>
</html>
