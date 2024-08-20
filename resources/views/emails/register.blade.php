@component('mail::message')
# Hello, {{ $user->name }}

Here is your verification code:

@component('mail::panel')
<center>{{ $user->code_verify_email }}</center>
@endcomponent

Please use this code to verify your email address.

If you did not create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
