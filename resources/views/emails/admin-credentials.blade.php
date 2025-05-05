@component('mail::message')
# Bonjour {{ $prenom }} {{ $nom }},

Votre compte administrateur a été créé avec succès sur {{ config('app.name') }}.

**Voici vos identifiants de connexion :**
🔐 **Email:** {{ $email }}
🔑 **Mot de passe temporaire:** `{{ $password }}`

@component('mail::panel')
Pour des raisons de sécurité, vous devrez changer ce mot de passe lors de votre première connexion.
@endcomponent

@component('mail::button', ['url' => $loginUrl])
Vérifier mon email et me connecter
@endcomponent

**Sécurité importante :**
Ne partagez jamais vos identifiants et changez votre mot de passe régulièrement.

Merci,
L'équipe {{ config('app.name') }}
@endcomponent
