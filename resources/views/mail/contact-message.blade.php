<x-mail::message>
@if ($acknowledgement)
# Bonjour {{ $contactMessage->first_name }},

Nous avons bien reçu votre message et nous vous répondrons rapidement.

@else
# Nouveau message depuis le site

**De :** {{ $contactMessage->first_name }} {{ $contactMessage->last_name }} ({{ $contactMessage->email }})<br>
**Profil :** {{ config("amap.contact_sender_types.{$contactMessage->sender_type}") }}<br>
**Sujet :** {{ config("amap.contact_subjects.{$contactMessage->subject}.label") }}

@endif
<x-mail::panel>
{{ $contactMessage->message }}
</x-mail::panel>

{{ config('amap.name') }}
</x-mail::message>
