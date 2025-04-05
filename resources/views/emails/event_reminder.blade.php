<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rappel d'événement</title>
</head>
<body>
    <h1>Rappel : votre événement approche</h1>
    <p>Bonjour {{ $user->name }},</p>
    <p>Ceci est un rappel pour l'événement "<strong>{{ $event->title }}</strong>" prévu le {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y H:i') }}.</p>
    <p>A bientôt !</p>
</body>
</html>
