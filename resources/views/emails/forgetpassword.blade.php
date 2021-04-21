@component('mail::message')
# Introduction


@component('mail::button', ['url' => $url])
Reset your password.
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent