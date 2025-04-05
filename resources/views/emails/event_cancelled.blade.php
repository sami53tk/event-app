<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Annulation de l'événement</title>
</head>
<body>
    <h1>Annulation de l'événement</h1>
    <p>Bonjour {{ $user->name }},</p>
    <p>Nous vous informons que l'événement "<strong>{{ $event->title }}</strong>" prévu le {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y H:i') }} a été annulé.</p>
    <p>Nous sommes désolés pour la gêne occasionnée.</p>
</body>
</html>
