<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Redirection si non connecté
        if (!$user) {
            return redirect()->route('login');
        }

        // Vérifie si l'utilisateur a confirmé son email (avec le champ email_verified_at)
        if (is_null($user->email_verified_at)) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre adresse e-mail n\'a pas encore été vérifiée.',
            ]);
        }

        // Redirection en fonction du rôle (si route de redirection)
        if ($request->routeIs('role.redirect')) {
            switch ($user->role) {
                case User::ROLE_SUPER_ADMIN:
                    return redirect()->route('SuperAdmin.Home');
                case User::ROLE_ADMIN_CS:
                    return redirect()->route('AdminCS.Home');
                case User::ROLE_MEDECIN:
                    return redirect()->route('Medecin.Home');
                case User::ROLE_SECRETAIRE:
                    return redirect()->route('Secretaire.Home');
                case User::ROLE_LABORANTIN:
                    return redirect()->route('Laborantin.Home');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'role' => 'Rôle utilisateur inconnu.'
                    ]);
            }
        }

        // Vérifie si la route nécessite un rôle spécifique
        $requiredRole = $request->route()->getAction('role');
        if ($requiredRole && $user->role !== $requiredRole) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'auth' => 'Vous n\'avez pas les droits nécessaires.'
            ]);
        }

        return $next($request);
    }
}
