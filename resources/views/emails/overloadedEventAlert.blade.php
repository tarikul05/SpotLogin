<!DOCTYPE html>
<html>
<head>
    <title>Alerte de surcharge d'événements</title>
</head>
<body>
    <h1>Alerte de surcharge d'événements</h1>
    <b>Type d'alerte :</b> entrées mysql dans la table "events"<br />
    <b>Location:</b> Serveur de production</b><br /><br />
    <hr>
    <p>Le système a détecté une surcharge d'événements dans la base de données.</p>
    <p>Au cours de la dernière heure : <b>{{ $eventCount }}</b></p>
</body>
</html>
