<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Facture;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Secretaire;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SecretaireController extends Controller
{
    //
    public function Home()
    {
        // Récupérer l'ID du centre de santé de l'utilisateur authentifié
        $centreId = Auth::user()->centreDeSante->id;
        $today = Carbon::today(); // Obtient la date d'aujourd'hui sans l'heure

        // 1. Calculer le nombre de patients enregistrés aujourd'hui dans ce centre
        // On utilise 'whereHas' pour filtrer les patients qui ont une relation pivot avec le centre de l'utilisateur
        // et dont l'enregistrement a eu lieu aujourd'hui.
        $patientsToday = Patient::whereHas('centres', function ($query) use ($centreId, $today) {
            $query->where('centre_de_sante_id', $centreId)
                ->whereDate('date_enregistrement', $today);
        })->count();

        // 2. Calculer le nombre de rendez-vous programmés pour aujourd'hui dans ce centre
        // On filtre par le centre et par la date du jour pour 'date_heure'.
        // Les statuts 'programmé', 'confirmé' et 'en cours' sont considérés comme des RDV "programmés".
        $rdvProgrammes = RendezVous::where('centre_de_sante_id', $centreId)
            ->whereDate('date_heure', $today)
            ->whereIn('etat',  [
                'planifié',
                'respecté',
                'annulé',
                'reporté',
            ])
            ->count();

        // 3. Calculer le nombre de factures à régler (impayées) pour ce centre
        // On utilise le scope 'impayees' défini dans le modèle Facture pour plus de clarté.
        $facturesARegler = Facture::where('centre_de_sante_id', $centreId)
            ->impayees()
            ->count();

        // 4. Calculer le nombre d'analyses en attente pour ce centre
        // On filtre par le centre et par le statut 'en attente'.
        $analysesEnAttente = Analyse::where('centre_de_sante_id', $centreId)
            ->where('statut', 'en attente')
            ->count();

        // Passe les statistiques à la vue Inertia
        return Inertia::render('Secretaire/Home', [
            'stats' => [
                'patientsToday' => $patientsToday,
                'rdvProgrammes' => $rdvProgrammes,
                'facturesARegler' => $facturesARegler,
                'analysesEnAttente' => $analysesEnAttente,
            ]
        ]);
    }


    public function V_Dashboard()
    {
        return Inertia::render('Secretaire/Dashboard');
    }


    public function V_CreateP(Request $request)
    {

        $centreId = Auth::user()->centreDeSante->id;
        $secretaire = Secretaire::findOrFail(Auth::user()->id);

        $query = $secretaire->patients()
            ->with('dossierMedical')
            ->with('centres')
            ->where('status', 'actif')
            ->orderBy('created_at', 'desc');

        // Filtre par recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhere('npi', 'like', "%{$search}%");
            });
        }

        // Filtre par genre
        if ($request->has('genre') && !empty($request->genre)) {
            $query->where('genre', $request->genre);
        }

        $patients = $query->paginate(6)->withQueryString();

        // Requête pour les médecins sans consultation en cours
        $medecinsLibres = Medecin::where('role', 'medecin')
            ->where('centre_de_sante_id' , $centreId)
            ->whereDoesntHave('consultations', function ($query) {
                $query->where('status', 'en cours');
            })
            ->get(['id', 'nom', 'prenom']);

        // dd($medecinsLibres);

        return Inertia::render('Secretaire/CreateP', [
            'patients' => $patients,
            'medecinsLibres' => $medecinsLibres,
            'filters' => $request->only(['search', 'genre'])
        ]);
    }

    public function store(Request $request)
    {
        $centreId = Auth::user()->centreDeSante->id;
        $request->merge(['centre_de_sante_id' => $centreId]);

        // Validation des données de la requête
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'genre' => 'required|in:M,F',
            'email' => [
                'nullable',
                'email',
                // Rule::unique('patients', 'email')->ignore($patient->id ?? null), // Peut être ajouté si tu modifies un patient existant
            ],
            'telephone' => [
                'required',
                'string',
                'max:20',
                // Rule::unique('patients', 'telephone')->ignore($patient->id ?? null),
            ],
            'adresse' => 'nullable|string',
            'npi' => [
                'nullable',
                'string',
                'max:50',
                // Rule::unique('patients', 'npi')->ignore($patient->id ?? null),
            ],

            'status' => 'required|in:actif,inactif',
            'centre_de_sante_id' => 'required|exists:centre_de_santes,id'
        ]);

        try {

            $existingPatientByUniqueFields = null;
            $duplicateField = null;

            // Recherche par email
            if (!empty($validated['email'])) {
                $existingPatientByUniqueFields = Patient::where('email', $validated['email'])->first();
                if ($existingPatientByUniqueFields) {
                    $duplicateField = 'email';
                }
            }

            // Recherche par téléphone (seulement si pas déjà trouvé par email)
            if (!$existingPatientByUniqueFields && !empty($validated['telephone'])) {
                $existingPatientByUniqueFields = Patient::where('telephone', $validated['telephone'])->first();
                if ($existingPatientByUniqueFields) {
                    $duplicateField = 'telephone';
                }
            }

            // Recherche par NPI (seulement si pas déjà trouvé par email ou téléphone)
            if (!$existingPatientByUniqueFields && !empty($validated['npi'])) {
                $existingPatientByUniqueFields = Patient::where('npi', $validated['npi'])->first();
                if ($existingPatientByUniqueFields) {
                    $duplicateField = 'npi';
                }
            }

            $patient = null;
            if ($existingPatientByUniqueFields) {
                // Un patient a été trouvé par un champ unique.
                // Vérifions si ce patient est le même que celui qu'on trouverait par nom/prénom/date_naissance.
                $patientByBasicInfo = Patient::where('nom', $validated['nom'])
                    ->where('prenom', $validated['prenom'])
                    ->whereDate('date_naissance', $validated['date_naissance']) // Option 1: Recommandé par Laravel
                    ->first();

                // dd($patientByBasicInfo);

                // *** Nouvelle logique pour la vérification du pivot spécifique au centre ***
                $pivotDataForCurrentCentre = $existingPatientByUniqueFields->centres()
                    ->where('centre_de_sante_id', $validated['centre_de_sante_id'])
                    ->first();

                if ($patientByBasicInfo && $patientByBasicInfo->id === $existingPatientByUniqueFields->id) {
                    // C'est le même patient, trouvé par un autre critère ou par infos basiques.
                    // Si le patient est inactif dans ce centre, on le réactive.
                    if ($pivotDataForCurrentCentre && $pivotDataForCurrentCentre->pivot->status === 'inactif') {
                        $existingPatientByUniqueFields->centres()->updateExistingPivot($validated['centre_de_sante_id'], [
                            'status' => 'actif',
                            'created_by_user_id' => Auth::id(),
                            'date_enregistrement' => now(),
                        ]);
                        return redirect()->route('Secretaire.CreateP')
                            ->with('success', 'Patient réactivé dans ce centre avec succès.')
                            ->with('patient', $existingPatientByUniqueFields);
                    } elseif ($pivotDataForCurrentCentre && $pivotDataForCurrentCentre->pivot->status === 'actif') {
                        // Patient déjà actif dans ce centre
                        return redirect()->route('Secretaire.CreateP')
                            ->with('error', 'Ce patient est déjà enregistré dans ce centre et est actif.')
                            ->with('patient', $existingPatientByUniqueFields);
                    } elseif (!$pivotDataForCurrentCentre) {
                        // Le patient existe globalement mais n'est pas encore lié à ce centre
                        $existingPatientByUniqueFields->centres()->attach($validated['centre_de_sante_id'], [
                            'created_by_user_id' => Auth::id(),
                            'date_enregistrement' => now(),
                            'status' => $validated['status'],
                        ]);
                        return redirect()->route('Secretaire.CreateP')
                            ->with('success', 'Patient ajouté à ce centre avec succès.')
                            ->with('patient', $existingPatientByUniqueFields);
                    }
                    // Si le statut est inactif et qu'on essaie de le mettre inactif, ou autre cas, ne rien faire
                } else {
                    // Conflit : un AUTRE patient existe avec cet email/téléphone/NPI.
                    // Ou, si le patient trouvé par champs uniques n'est PAS le même que celui par infos basiques,
                    // il y a un problème de "doublon logique".
                    $errorMessage = "Un patient avec le même " . ($duplicateField === 'email' ? "email" : ($duplicateField === 'telephone' ? "numéro de téléphone" : "NPI")) . " existe déjà.";
                    return redirect()->route('Secretaire.CreateP')
                        ->with('error', $errorMessage);
                }
                $patient = $existingPatientByUniqueFields; // Assigner si on continue après les checks
            } else {
                // Aucun patient trouvé par les champs uniques, recherchons par nom/prénom/date_naissance.
                $patient = Patient::where('nom', $validated['nom'])
                    ->where('prenom', $validated['prenom'])
                    ->where('date_naissance', $validated['date_naissance'])
                    ->first();
            }

            // Si un patient est trouvé (soit par infos uniques, soit par infos basiques)
            if ($patient) {
                // Le patient existe dans le système.
                // Vérifier s'il est déjà lié à ce centre et quel est son statut DANS CE CENTRE.
                $pivotData = $patient->centres()
                    ->where('centre_de_sante_id', $validated['centre_de_sante_id'])
                    ->first(); // Utilise first() pour obtenir la relation pivot si elle existe

                if ($pivotData) {
                    // Le patient est déjà lié à ce centre (via la table pivot)
                    // Accéder au statut via la relation pivot
                    $currentStatusInCentre = $pivotData->pivot->status;

                    if ($currentStatusInCentre === 'actif' && $validated['status'] === 'actif') {
                        // Patient déjà actif et enregistré dans ce centre, et on essaie de le remettre actif
                        return redirect()->route('Secretaire.CreateP')
                            ->with('warning', 'Ce patient est déjà enregistré dans ce centre et est actif.')
                            ->with('patient', $patient);
                    } elseif ($currentStatusInCentre === 'inactif' && $validated['status'] === 'actif') {
                        // Patient existe dans ce centre mais est inactif, on le réactive dans ce centre
                        $patient->centres()->updateExistingPivot($validated['centre_de_sante_id'], [
                            'status' => 'actif',
                            'created_by_user_id' => Auth::id(), // Mettre à jour qui l'a réactivé/mis à jour
                            'date_enregistrement' => now(), // Mettre à jour la date d'enregistrement/réactivation
                        ]);
                        return redirect()->route('Secretaire.CreateP')
                            ->with('success', 'Patient réactivé dans ce centre avec succès.')
                            ->with('patient', $patient);
                    }
                    // Si $currentStatusInCentre est 'actif' et $validated['status'] est 'inactif', on ne fait rien
                    // Ou tu pourrais ajouter une logique pour désactiver le patient dans le centre si besoin
                } else {
                    // Le patient existe dans le système mais n'est pas encore lié à ce centre
                    // Lier au nouveau centre avec le statut demandé
                    $patient->centres()->attach($validated['centre_de_sante_id'], [
                        'created_by_user_id' => Auth::id(),
                        'date_enregistrement' => now(),
                        'status' => $validated['status'], // Utilise le statut de la validation
                    ]);

                    return redirect()->route('Secretaire.CreateP')
                        ->with('success', 'Patient ajouté à ce centre avec succès.')
                        ->with('patient', $patient);
                }
            } else {
                // Aucun patient trouvé : créer un nouveau patient
                $patient = Patient::create($validated);

                // Lier au centre avec le statut initial
                $patient->centres()->attach($validated['centre_de_sante_id'], [
                    'created_by_user_id' => Auth::id(),
                    'date_enregistrement' => now(),
                    'status' => $validated['status'], // Utilise le statut de la validation (normalement 'actif')
                ]);

                return redirect()->route('Secretaire.CreateP')
                    ->with('success', 'Nouveau patient enregistré avec succès.')
                    ->with('patient', $patient);
            }

            // Retour par défaut si aucune redirection n'a eu lieu (scenario non géré)
            return redirect()->route('Secretaire.CreateP')
                ->with('info', 'Opération patient terminée sans changement majeur.');
        } catch (Exception $e) {
            // Pour les erreurs de base de données inattendues
            return redirect()->route('Secretaire.CreateP')
                ->with('error', 'Erreur inattendue lors de l\'enregistrement du patient : ' . $e->getMessage());
        }
    }

    // Mettre à jour un patient
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $centreId = Auth::user()->centreDeSante->id; // Récupère l'ID du centre de l'utilisateur

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'genre' => 'required|in:M,F',
            'email' => [
                'nullable',
                'email',
                // Règle d'unicité : l'email doit être unique dans la table 'patients',
                // mais on ignore le patient en cours de modification ($id).
                Rule::unique('patients', 'email')->ignore($patient->id),
            ],
            'telephone' => [
                'required',
                'string',
                'max:20',
                // Idem pour le téléphone
                Rule::unique('patients', 'telephone')->ignore($patient->id),
            ],
            'adresse' => 'nullable|string',
            'npi' => [
                'nullable',
                'string',
                'max:50',
                // Idem pour le NPI
                Rule::unique('patients', 'npi')->ignore($patient->id),
            ],
            // Le statut est maintenant pour la table pivot, il est "sometimes" car il ne sera pas toujours envoyé
            // (ex: si on ne met à jour que l'adresse). Mais s'il est envoyé, il est requis et doit être 'actif' ou 'inactif'.
            'status' => 'sometimes|required|in:actif,inactif',
        ]);

        try {
            // Mettre à jour les informations du patient sur le modèle Patient
            // On ne passe pas le 'status' ici car il est sur la pivot
            $patientDataForUpdate = $validated;
            unset($patientDataForUpdate['status']); // Supprime le status des données du patient

            $patient->update($patientDataForUpdate);

            // Gérer la mise à jour du statut dans la table pivot 'centre_patient'
            // Seulement si le statut est envoyé dans la requête
            if (isset($validated['status'])) {
                // S'assurer que le patient est bien attaché à ce centre avant de tenter de mettre à jour le pivot
                $pivotExists = $patient->centres()->wherePivot('centre_de_sante_id', $centreId)->exists();

                if ($pivotExists) {
                    $patient->centres()->updateExistingPivot($centreId, [
                        'status' => $validated['status'],
                        'created_by_user_id' => Auth::id(), // Optionnel: mettre à jour l'utilisateur qui a modifié
                        'date_enregistrement' => now(), // Optionnel: mettre à jour la date de dernière modification
                    ]);
                    $message = 'Patient et son statut dans le centre mis à jour avec succès.';
                } else {
                    // Si le patient n'était pas attaché à ce centre mais on tente de mettre à jour son statut
                    // On pourrait choisir de l'attacher maintenant, ou de signaler une erreur.
                    // Pour l'instant, on suppose qu'un patient que l'on modifie est déjà dans notre centre.
                    // Si ce n'est pas le cas, c'est une situation anormale.
                    $message = 'Patient mis à jour avec succès, mais le statut du centre n\'a pas pu être modifié car le patient n\'y est pas attaché.';
                }
            } else {
                $message = 'Patient mis à jour avec succès.';
            }

            return redirect()->route('Secretaire.CreateP')
                ->with('success', $message)
                ->with('patient', $patient); // Renvoie le patient mis à jour
        } catch (Exception $e) {
            // Laravel gérera déjà les erreurs d'unicité via la validation.
            // Ce bloc catch est pour les erreurs inattendues de base de données ou autres.
            return redirect()->route('Secretaire.CreateP')
                ->with('error', 'Erreur lors de la mise à jour du patient : ' . $e->getMessage());
        }
    }

    // Supprimer un patient
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $centreId = Auth::user()->centreDeSante->id; // Récupère l'ID du centre de l'utilisateur

        try {
            // Vérifier si le patient est attaché à ce centre
            $pivot = $patient->centres()
                ->wherePivot('centre_de_sante_id', $centreId)
                ->first();

            if ($pivot) {
                // Le patient est attaché. Mettre à jour son statut dans la table pivot.
                $patient->centres()->updateExistingPivot($centreId, [
                    'status' => 'inactif',
                    'created_by_user_id' => Auth::id(), // Qui a désactivé le patient
                    'date_enregistrement' => now(), // Date de la désactivation
                ]);

                $message = 'Patient désactivé dans ce centre avec succès.';
                $type = 'success';
            } else {
                // Le patient n'est pas attaché à ce centre, impossible de changer son statut ici.
                $message = 'Ce patient n\'est pas enregistré dans votre centre, impossible de le désactiver ici.';
                $type = 'warning';
            }

            return redirect()->back()->with($type, $message);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la désactivation du patient : ' . $e->getMessage());
        }
    }
}
