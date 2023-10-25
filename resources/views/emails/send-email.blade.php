@component('mail::message')
# Email Subject: {{ $subject }}

{!! $messageContent !!}
@endcomponent
