<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inscription à l'événement</title>
</head>
<body>
    <h1>Inscription confirmée</h1>
    <p>Bonjour,</p>
    <p>Vous êtes inscrit(e) à l'événement "<strong>{{ $event->title }}</strong>" prévu le {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y H:i') }}.</p>
    <p>Merci de votre participation !</p>
</body>
</html>
