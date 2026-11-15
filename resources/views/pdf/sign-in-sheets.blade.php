<!doctype html>
<html lang="fr"><head><meta charset="utf-8">@include('pdf._style')</head>
<body>
<footer>{{ config('amap.name') }} — feuille d'émargement — distribution du {{ $distribution->date->translatedFormat('j F Y') }}</footer>
@forelse ($sheets as $title => $rows)
    <header>
        <img src="{{ public_path('images/logo-600.png') }}" alt="">
        <h1>{{ $title }}</h1>
        <div class="meta">Feuille d'émargement — distribution n°{{ $distribution->number() }} du {{ lcfirst($distribution->label()) }}<br>{{ $distribution->place }}, {{ $distribution->timeRange() }}</div>
    </header>
    <table>
        <tr><th>Nom de l'adhérent</th><th class="qty">Quantité</th><th style="width:45%">Signature</th></tr>
        @foreach ($rows as $row)
            <tr><td>{{ $row['user']->sort_name }}</td><td class="qty">{{ $row['quantity'] }}</td><td style="height:22px"></td></tr>
        @endforeach
        @for ($i = 0; $i < 3; $i++)
            <tr><td style="height:22px"></td><td></td><td></td></tr>
        @endfor
        <tr class="total"><td>Total</td><td class="qty">{{ $rows->sum('quantity') }}</td><td></td></tr>
    </table>
    @unless ($loop->last)<div class="page-break"></div>@endunless
@empty
    <header><h1>Aucune livraison prévue</h1><div class="meta">{{ $distribution->label() }}</div></header>
@endforelse
</body></html>
