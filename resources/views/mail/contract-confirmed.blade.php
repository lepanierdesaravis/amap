@php($offer = $contract->offer)
<x-mail::message>
# Merci {{ $contract->user->first_name }} !

Votre contrat **{{ $offer->title }}** avec **{{ $offer->producer->name }}** est enregistré.

<x-mail::table>
| Produit | Quantité | Livraisons | Total |
|:--|:--:|:--:|--:|
@foreach ($contract->lines as $line)
| {{ $line->product->name }} | {{ $line->quantity }} | {{ count($line->distribution_ids) }} | {{ \App\Support\Money::format($line->totalCents()) }} |
@endforeach
| **Total** | | | **{{ \App\Support\Money::format($contract->total_cents) }}** |
</x-mail::table>

## Vos règlements ({{ $contract->payment_method->getLabel() }})

@foreach ($contract->installmentsDue as $i)
- {{ \App\Support\Money::format($i->amount_cents) }} — encaissement en {{ $i->due_on->translatedFormat('F Y') }}
@endforeach

@if ($contract->payment_method === \App\Enums\PaymentMethod::Cheque && $offer->producer->cheque_payee)
Chèques à l'ordre de **{{ $offer->producer->cheque_payee }}**, à remettre à votre référent. Indiquez au dos le mois d'encaissement.
@elseif ($offer->producer->iban)
Virement sur le compte du producteur : {{ $offer->producer->iban }}
@endif

Vos dates de livraison sont dans votre calendrier.

<x-mail::button :url="route('espace.calendrier')" color="success">
Voir mon calendrier
</x-mail::button>

À bientôt à la distribution,<br>
{{ config('amap.name') }}
</x-mail::message>
