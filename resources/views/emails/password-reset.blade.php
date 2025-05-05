<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation de mot de passe</title>
</head>
<body>
    <h1>Bonjour {{ $user->nom }}   {{ $user->prenom }},</h1>
    <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
    <p>
        <a href="{{ $resetUrl }}">Cliquez ici pour réinitialiser votre mot de passe</a>
    </p>
    <p>Ce lien expirera dans 60 minutes.</p>
    <p>Si vous n'avez pas demandé cette réinitialisation, ignorez simplement cet email.</p>
</body>
</html>
