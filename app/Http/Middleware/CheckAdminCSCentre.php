<?php

namespace App\Http\Middleware;

use App\Models\CentreDeSante;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminCSCentre
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Vérifie si l'utilisateur est un AdminCS
        if ($user && $user->role === 'admin_cs') {

            // Exclure la route de création de centre de la vérification
            if ($request->routeIs('AdminCS.Centre')) {
                return $next($request);
            }

            // Charger la relation (nom doit correspondre au modèle)
            if (!$user->relationLoaded('centreDeSante')) {
                $user->load('centreDeSante');
            }

            // Vérifier l'existence du centre
            $hasCentre = $user->centreDeSante || CentreDeSante::where('admin_c_s_id', $user->id)->exists();

            if (!$hasCentre) {
                return redirect()->route('AdminCS.Centre');
            }
        }

        return $next($request);
    }
}
