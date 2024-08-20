@component('mail::message')
# Hello, {{ $user->name }}

We understand it happens.

Here is your password reset code:

@component('mail::panel')
<center>{{ $user->code_verify_email }}</center>
@endcomponent

In case you have any issues recovering your password, please contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
