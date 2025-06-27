@component('mail::message')
# Hello, {{ $name }}!

You requested a password reset.

@component('mail::button', ['url' => $redirectUrl])
Reset in Flutter App
@endcomponent

If you did not request this, please ignore this email.

Thanks,  
{{ config('app.name') }}
@endcomponent
