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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Si l'utilisateur essaie d'accéder à la redirection de rôle
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

        // Vérification des rôles pour les autres routes
        $requiredRole = $request->route()->getAction('role');
        if ($user->role !== $requiredRole) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'auth' => 'Vous n\'avez pas les droits nécessaires.'
            ]);
        }

        return $next($request);
    }
}
