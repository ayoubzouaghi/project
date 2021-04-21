<!-- <!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
</head>

<body>
    <h2>Contunier</h2>
    <ul>
        <li><strong>Nom</strong> : {{ $email }}</li>
        @component('mail::button', ['url' => $url, 'color' => 'success'])

    </ul>
</body>

</html> -->
@component('mail::message')
{{ $email }}

The body of your message.

@component('mail::button', ['url' => $url])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent