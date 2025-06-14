<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\Laborantin;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MedecinController extends Controller
{
    //
    public function Home()
    {
        return Inertia::render('Medecin/Home');
    }

    public function InfosPatient(Request $request)
    {
        $query = Patient::with('dossierMedical.consultations'); // Charger le dossier médical et les consultations

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        $patients = $query->paginate(15);

        return Inertia::render('Medecin/Patient', [
            'patients' => $patients,
            'filters' => $request->only(['search'])
        ]);
    }

    public function V_Historique()
    {
        // Récupérer l'ID du médecin connecté
        $medecinId = Auth::user()->id;
        $centreId = Auth::user()->centreDeSante->id;

        // Récupérer les consultations avec les relations nécessaires
        $consultations = Consultation::with(['medecin', 'dossierMedical.patient'])
            ->where('medecin_id', $medecinId)
            ->orderByRaw("CASE WHEN status = 'en cours' THEN 0 ELSE 1 END")
            ->orderBy('date_consultation', 'desc')
            ->get()
            ->map(function ($consultation) {
                return [
                    'id' => $consultation->id,
                    'motif' => $consultation->motif,
                    'date_consultation' => $consultation->date_consultation,
                    'status' => $consultation->status,
                    'poids' => $consultation->poids,
                    'taille' => $consultation->taille,
                    'temperature' => $consultation->temperature,
                    'tension_arterielle' => $consultation->tension_arterielle,
                    'diagnostic' => $consultation->diagnostic,
                    'ordonnance' => $consultation->ordonnance,
                    'medecin' => [
                        'id' => $consultation->medecin->id,
                        'nom' => $consultation->medecin->nom,
                        'prenom' => $consultation->medecin->prenom,
                    ],
                    'patient' => $consultation->dossierMedical->patient ? [
                        'id' => $consultation->dossierMedical->patient->id,
                        'nom' => $consultation->dossierMedical->patient->nom,
                        'prenom' => $consultation->dossierMedical->patient->prenom,
                    ] : null,
                    'created_at' => $consultation->created_at,
                    'updated_at' => $consultation->updated_at,
                ];
            });

            $laborantins = Laborantin::latest()
                ->where('centre_de_sante_id' , $centreId)
                ->get(['id', 'nom', 'prenom']);

        return Inertia::render('Medecin/Consultation', [
            'consultations' => $consultations,
            'laborantins' => $laborantins
        ]);
    }
}
