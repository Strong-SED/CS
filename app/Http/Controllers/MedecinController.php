<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\Laborantin;
use App\Models\Patient;
use App\Models\RendezVous;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MedecinController extends Controller
{
    //
    public function Home()
    {
        $user = Auth::user(); // Récupère l'utilisateur médecin connecté

        // Assurez-vous que l'utilisateur est bien un médecin
        if (!$user || $user->role !== 'medecin') {
            // Redirige vers la page de connexion ou un tableau de bord par défaut avec une erreur
            return redirect()->route('login')->with('error', 'Accès non autorisé. Vous devez être connecté en tant que médecin.');
        }

        // --- Récupération des statistiques pour le jour courant ---
        $today = now()->toDateString(); // Date d'aujourd'hui au format 'YYYY-MM-DD'

        // Total des rendez-vous programmés pour aujourd'hui par ce médecin (tous statuts confondus)
        $rdvProgrammes = RendezVous::where('medecin_id', $user->id)
                                    ->whereDate('date_heure', $today) // Utilise date_heure pour le RDV
                                    ->count();

        // Total des consultations effectuées aujourd'hui par ce médecin
        $consultationsEffectuees = Consultation::where('medecin_id', $user->id)
                                                ->whereDate('date_consultation', $today)
                                                ->count();

        // Total des patients vus aujourd'hui (patients uniques via les consultations)
        $patientsVusAujourdhui = Consultation::where('medecin_id', $user->id)
                                            ->whereDate('date_consultation', $today)
                                            ->distinct('dossier_medical_id') // Utilise dossier_medical_id pour obtenir des patients uniques via les consultations
                                            ->count();

        // Rendez-vous en attente pour aujourd'hui :
        // Ce sont les RDV programmés pour aujourd'hui qui n'ont pas un statut 'terminé' ou 'annulé'.
        // ASSUMPTION CLÉ : Le champ 'etat' du RendezVous est mis à jour à 'terminé'
        // quand la consultation associée est achevée.
        $rdvEnAttente = RendezVous::where('medecin_id', $user->id)
                                ->whereDate('date_heure', $today)
                                ->whereIn('etat', ['programmé', 'confirmé', 'en cours']) // Statuts qui indiquent un RDV en attente
                                ->count();


        // --- Récupération des rendez-vous du jour courant avec les détails du patient ---
        $rendezVousDuJour = RendezVous::where('medecin_id', $user->id)
                                    ->whereDate('date_heure', $today) // Utilise date_heure pour le RDV
                                    ->with('patient') // Charge les informations du patient associé
                                    ->orderBy('date_heure') // Trie par date et heure
                                    ->get();

        // Prépare les données pour la vue
        $rdvFormatted = $rendezVousDuJour->map(function ($rdv) {
            // Pour 'patient_nom_complet', assurez-vous de gérer le cas où $rdv->patient est null
            $patientNomComplet = $rdv->patient ? ($rdv->patient->nom . ' ' . $rdv->patient->prenom) : 'Patient Inconnu';
            $patientId = $rdv->patient ? $rdv->patient->id : 'N/A';

            return [
                'id' => $rdv->id,
                'patient_id' => $patientId,
                'patient_nom_complet' => $patientNomComplet,
                'heure' => Carbon::parse($rdv->date_heure)->format('H:i'), // Utilisez date_heure pour l'heure du RDV
                'motif' => $rdv->motif ?? 'Non spécifié',
                'statut' => $rdv->etat, // Le statut du RDV (programmé, terminé, etc.)
                // Détermine si une consultation a déjà eu lieu pour ce RDV basé sur son statut
                'has_consultation' => in_array($rdv->etat, ['terminé', 'effectué']) // Adaptez les statuts réels si différents
            ];
        });


        return Inertia::render('Medecin/Home', [
            // L'utilisateur est déjà partagé via le middleware, mais le passer explicitement ici
            // assure que toutes les relations (comme centreDeSante) sont disponibles.
            'user' => $user->load('centreDeSante'),
            'stats' => [
                'patients_aujourdhui' => $patientsVusAujourdhui,
                'rdv_programmes' => $rdvProgrammes,
                'consultations_effectuees' => $consultationsEffectuees,
                'rdv_en_attente' => $rdvEnAttente, // Renommé pour correspondre à la vue
            ],
            'rendezVousDuJour' => $rdvFormatted,
        ]);
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
