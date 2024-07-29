@component('mail::message')
# Verify Your Email

Click the button below to verify your email address:

@component('mail::button', ['url' => $verificationUrl])
Verify Email
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
