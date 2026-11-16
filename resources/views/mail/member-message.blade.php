@php($sender = $memberMessage->sender)
<x-mail::message>
# {{ $memberMessage->subject }}

**{{ $sender->name }}** vous écrit
@switch ($memberMessage->audience->value)
@case('adherents') depuis l'espace « Échanges entre adhérents ».
@break
@case('referent') au sujet de {{ $memberMessage->producer?->name }}, dont vous êtes le référent.
@break
@default à l'attention du bureau.
@endswitch

<x-mail::panel>
{{ $memberMessage->body }}
</x-mail::panel>

Pour répondre, utilisez simplement « Répondre » : votre réponse ira directement à {{ $sender->first_name }}.

@if ($memberMessage->audience->value === 'adherents')
<small>Vous recevez ce message car vous êtes adhérent·e de {{ config('amap.name') }}. Pour ne plus recevoir les messages des autres adhérents, décochez la case dans [Mon compte]({{ route('espace.compte') }}).</small>
@endif

{{ config('amap.name') }}
</x-mail::message>
