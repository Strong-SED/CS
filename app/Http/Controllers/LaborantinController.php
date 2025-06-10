<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Laborantin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LaborantinController extends Controller
{
    //
    public function Home()
    {
        // Get the ID of the currently authenticated laborantin
        $laborantinId = Auth::id();

        // If no laborantin is authenticated, you might want to redirect or show an error.
        // For now, we'll assume a laborantin is always logged in when accessing this page.
        if (!$laborantinId) {
            // Handle cases where no user is logged in, e.g., redirect to login
            return redirect()->route('login');
        }

        // Retrieve statistics, filtered by the authenticated laborantin's ID
        $analysesPrescrites = Analyse::where('laborantin_id', $laborantinId)
            ->where('statut', 'prescrit')
            ->count();

        $analysesEnCours = Analyse::where('laborantin_id', $laborantinId)
            ->where('statut', 'en_cours')
            ->count();

        $analysesTermineesAujourdhui = Analyse::where('laborantin_id', $laborantinId)
            ->where('statut', 'termine')
            ->whereDate('updated_at', Carbon::today()) // Use updated_at or date_analyse
            ->count();

        // Example: Get the latest analyses for the connected laborantin
        $recentAnalyses = Analyse::where('laborantin_id', $laborantinId)
            ->orderBy('date_analyse', 'desc')
            ->limit(5) // Get the 5 most recent analyses
            ->get();

        // Example: Get urgent/priority analyses for the connected laborantin
        // Assuming you have an 'is_urgent' or 'priority' field, or a specific type
        $urgentAnalyses = Analyse::where('laborantin_id', $laborantinId)
            ->where('statut', 'en_cours')
            ->where('type_analyse', 'like', '%urgence%') // Example filter for urgent types
            ->limit(3) // Limit to a few urgent ones
            ->get();

        return Inertia::render('Laborantin/Home', [
            'stats' => [
                'prescrites' => $analysesPrescrites,
                'enCours' => $analysesEnCours,
                'termineesAujourdhui' => $analysesTermineesAujourdhui,
            ],
            'recentAnalyses' => $recentAnalyses, // Pass recent analyses to the view
            'urgentAnalyses' => $urgentAnalyses, // Pass urgent analyses to the view
        ]);
    }

    public function V_Analyse()
    {
        $laborantinId = Auth::id(); // Récupère l'ID de l'utilisateur authentifié

        if (!$laborantinId) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }


        $laborantin = Laborantin::find($laborantinId);
        if (!$laborantin) {
            // Gérer le cas où l'utilisateur connecté n'est pas trouvé comme laborantin
            return redirect()->route('login')->with('error', 'Accès non autorisé.');
        }

        $analyses = Analyse::where('laborantin_id', $laborantin->id) // Utilisez $laborantin->id pour être sûr
                            ->with([
                                'consultation.dossierMedical.patient', // Chaîne de relations pour obtenir le patient
                                'centre' // Relation directe pour le centre
                            ])
                            ->orderBy('date_analyse', 'desc')
                            ->get();
                                
        return Inertia::render('Laborantin/Analyse', [
            'analyses' => $analyses,
            'flash' => session()->all(['success', 'error', 'info']),
        ]);
    }

    public function updateResult(Request $request, Analyse $analyse)
    {
        // Assurez-vous que le laborantin connecté est bien celui assigné à l'analyse
        if ($analyse->laborantin_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier cette analyse.');
        }

        $request->validate([
            'resultat' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $analyse->update([
                'resultat' => $request->resultat,
                'statut' => 'termine', // L'analyse est terminée une fois le résultat enregistré
            ]);

            return redirect()->back()->with('success', 'Résultat de l\'analyse enregistré avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement du résultat.');
        }
    }

    // Nouvelle méthode pour démarrer une analyse (passer de 'prescrit' à 'en_cours')
    public function startAnalyse(Request $request, Analyse $analyse)
    {
        // Assurez-vous que le laborantin connecté est bien celui assigné à l'analyse
        // et que le statut est 'prescrit' avant de le changer.
        if ($analyse->laborantin_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à démarrer cette analyse.');
        }

        if ($analyse->statut !== 'prescrit') {
            return redirect()->back()->with('info', 'L\'analyse a déjà été démarrée ou terminée.');
        }

        try {
            $analyse->update([
                'statut' => 'en_cours',
            ]);

            return redirect()->back()->with('success', 'Analyse démarrée avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors du démarrage de l\'analyse.');
        }
    }
}
