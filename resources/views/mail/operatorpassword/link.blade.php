@component('mail::message')
Hello

You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
