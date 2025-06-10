<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Facture;
use App\Models\Secretaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FactureController extends Controller
{
    //
    public function V_facture()
    {
        $secretaire = Secretaire::findOrFail(Auth::user()->id);

        $factures = $secretaire->factures()
            ->with('patient')->get(); // Supposons que tu as une relation 'patient' définie dans ton modèle Facture

        return Inertia::render('Secretaire/Facture', [
            'factures' => $factures, // C'est ici que tu passes les données à ta vue Vue.js
        ]);
    }

    public function updateStatus(Request $request, Facture $facture)
    {
        // 1. Validate the incoming request (optional but recommended)
        $request->validate([
            'statut' => ['required', 'string', 'in:paye'], // Ensures 'statut' is provided and is 'paye'
        ]);

        // 2. Update the facture's status
        $facture->statut = $request->statut;
        $facture->save();

        // 3. Traitement des analyses associées
        // Assure-toi que le champ 'details' est bien casté en 'array' dans ton modèle Facture.
        // Si ce n'est pas le cas, tu devras faire un json_decode($facture->details) ici.
        if ($facture->details && is_array($facture->details)) {
            $consultationId = $facture->details['consultation_id'] ?? null;
            $analysesCodes = $facture->details['analyses'] ?? []; // Supposons que 'analyses' est un tableau de codes (ex: ['NFS', 'GLYCEMIE'])

            if ($consultationId && !empty($analysesCodes)) {
                // Trouve toutes les analyses liées à cette consultation et ces types d'analyses,
                // et dont le statut est 'prescrit'.
                Analyse::where('consultation_id', $consultationId)
                       ->whereIn('type_analyse', $analysesCodes)
                       ->where('statut', 'prescrit')
                       ->update(['statut' => 'en_cours']); // Met à jour leur statut à 'en_cours'
            }
        }

        return redirect()->back()->with('success' , 'Facture Payée');
    }
}
