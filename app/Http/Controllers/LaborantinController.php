<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LaborantinController extends Controller
{
    //
    public function Home()
    {
        // Optionnel : Récupérer le laborantin connecté si vous souhaitez filtrer par lui
        // $laborantinId = Auth::id();

        // Récupérer les statistiques pour les cartes
        $analysesPrescrites = Analyse::where('statut', 'prescrit')->count();
        $analysesEnCours = Analyse::where('statut', 'en_cours')->count();
        $analysesTermineesAujourdhui = Analyse::where('statut', 'termine')
            ->whereDate('updated_at', Carbon::today()) // ou date_analyse, selon ce que vous voulez compter
            ->count();

        // Si vous avez d'autres données à passer (par exemple, les dernières analyses, des alertes spécifiques)
        // $lastAnalyses = Analyse::orderBy('created_at', 'desc')->limit(5)->get();
        // $urgentAnalyses = Analyse::where('statut', 'en_cours')->where('is_urgent', true)->get(); // Si vous avez un champ is_urgent

        return Inertia::render('Laborantin/Home', [
            'stats' => [
                'prescrites' => $analysesPrescrites,
                'enCours' => $analysesEnCours,
                'termineesAujourdhui' => $analysesTermineesAujourdhui,
            ],
            // 'lastAnalyses' => $lastAnalyses, // Décommentez si vous les utilisez dans la vue
            // 'urgentAnalyses' => $urgentAnalyses, // Décommentez si vous les utilisez dans la vue
        ]);
    }
}
