@component('mail::message')
# Hello {{ $contactUs->name }}

{!! nl2br(e($replyMessage)) !!}

@component('mail::panel')
Original message:

{{ $contactUs->message }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent