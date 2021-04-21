@component('mail::message')
# Introduction


@component('mail::button', ['url' => $url])
Complete your registration.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent