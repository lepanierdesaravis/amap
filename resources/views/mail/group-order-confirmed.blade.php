@php($purchase = $order->groupPurchase)
<x-mail::message>
# Merci {{ $order->user->first_name }} !

Votre commande **{{ $purchase->title }}** ({{ $purchase->producer->name }}) est enregistrée.

<x-mail::table>
| Article | Quantité | Prix |
|:--|:--:|--:|
@foreach ($order->lines as $line)
| {{ $line->item->fullName() }} | {{ $line->quantity }} | {{ \App\Support\Money::format($line->quantity * $line->unit_price_cents) }} |
@endforeach
| **Total** | | **{{ \App\Support\Money::format($order->total_cents) }}** |
</x-mail::table>

**Livraison :** {{ $purchase->distribution->label() }}, {{ $purchase->distribution->timeRange() }}, {{ $purchase->distribution->place }}.

@if ($purchase->payment_info)
**Paiement :** {{ $purchase->payment_info }}
@endif

Vous pouvez modifier votre commande jusqu'au {{ $purchase->closes_at->translatedFormat('l j F à H\hi') }}.

<x-mail::button :url="route('espace.commandes-groupees.show', $purchase)" color="success">
Voir ma commande
</x-mail::button>

{{ config('amap.name') }}
</x-mail::message>
