<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\Facture;
use App\Models\Secretaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    //
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dossier_medical_id' => [
                'required',
                'exists:dossier_medicals,id',
                // Ajoutez une règle personnalisée si vous voulez vérifier que le dossier médical
                // appartient au centre de santé de l'utilisateur connecté, par exemple.
                // Rule::exists('dossier_medicals', 'id')->where(function ($query) {
                //     $query->where('centre_de_sante_id', Auth::user()->centre_de_sante_id);
                // }),
            ],
            'medecin_id' => [
                'required',
                'exists:users,id',
            ],
            'date_consultation' => 'required|date',
            'motif' => 'required|string|max:1000', // Longueur raisonnable pour un motif
            'poids' => 'nullable|numeric|max:500', // Poids en kg, avec une limite max
            'taille' => 'nullable|numeric|max:300', // Taille en cm, avec une limite max
            'temperature' => 'nullable|numeric|between:30,45', // Température raisonnable
            'tension_arterielle' => 'nullable|string|max:20', // Format ex: 120/80
            'status' => 'in:en cours,terminé', // Le statut par défaut est 'en cours' si non fourni
        ]);

        try {
            // Assurez-vous que le dossier médical existe avant de créer la consultation
            $dossierMedical = DossierMedical::find($validated['dossier_medical_id']);

            if (!$dossierMedical) {
                return redirect()->back()->with('error', 'Dossier médical introuvable.');
            }

            // Création de la consultation
            $consultation = new Consultation();
            $consultation->dossier_medical_id = $validated['dossier_medical_id'];
            $consultation->medecin_id = $validated['medecin_id'];
            $consultation->date_consultation = $validated['date_consultation'];
            $consultation->motif = $validated['motif'];
            $consultation->poids = $validated['poids'] ?? null; // Utilisez l'opérateur null coalescent
            $consultation->taille = $validated['taille'] ?? null;
            $consultation->temperature = $validated['temperature'] ?? null;
            $consultation->tension_arterielle = $validated['tension_arterielle'] ?? null;
            $consultation->status = $validated['status'] ?? 'en cours'; // Définit 'en cours' par défaut si non spécifié

            $consultation->save();

            // Redirection avec un message de succès
            return redirect()->route('Secretaire.CreateP')->with('success', 'Consultation enregistrée avec succès.');
        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement de la consultation: ' . $e->getMessage());
        }
    }

        public function completeConsultation(Request $request)
        {
            $validated = $request->validate([
                'id' => 'required|exists:consultations,id',
                'diagnostic' => 'required|string',
                'traitement_prescrit' => 'nullable|string',
                'observations' => 'nullable|string',
                'analyses' => 'nullable|array',
                'montant' => 'required|numeric|min:0',
                'laborantin_id'=> 'nullable',
                // 'status'=> 'required',
            ]);

            // dd($validated);

            DB::transaction(function () use ($validated) {
                // 1. Mettre à jour la consultation
                $consultation = Consultation::findOrFail($validated['id']);
                $consultation->update([
                    'diagnostic' => $validated['diagnostic'],
                    'traitement_prescrit' => $validated['traitement_prescrit'],
                    'observations' => $validated['observations'],
                    'analyses' => $validated['analyses'],
                    'status' => "terminé",
                    // 'date_fin' => now(),
                ]);

                // 2. Créer la facture
                $patientId = $consultation->dossierMedical->patient_id;
                $centreId = Auth::user()->centre_de_sante_id;

            // Trouver l'ID d'une secrétaire dans ce même centre de santé
            // Nous cherchons un utilisateur avec le rôle de secrétaire et le même centre_de_sante_id.
            // Si plusieurs secrétaires existent, on prend le premier trouvé.
            $secretaire = Secretaire::where('centre_de_sante_id', $centreId)->first();

            // Vérifier si une secrétaire a été trouvée
            if (!$secretaire) {
                // Si aucune secrétaire n'est trouvée, lancez une exception
                // ou gérez cette situation selon la logique de votre application.
                // Par exemple, vous pourriez vouloir qu'une facture ne puisse pas être créée sans secrétaire.
                throw new \Exception("Aucune secrétaire trouvée pour le centre de santé actuel (ID: {$centreId}). Impossible de créer la facture.");
            }

                Facture::create([
                    'patient_id' => $patientId,
                    'secretaire_id' => $secretaire->id,
                    'centre_de_sante_id' => $centreId,
                    'numero_facture' => 'FACT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                    'date_emission' => now(),
                    'montant' => $validated['montant'], // ✅ AJOUT ICI
                    'statut' => 'impaye',
                    'details' => json_encode([
                        'consultation_id' => $consultation->id,
                        'analyses' => $validated['analyses'],
                        'diagnostic' => $validated['diagnostic'],
                    ]),
                ]);



                // 3. Si des analyses sont prescrites, créer des entrées dans la table analyses_patient
                if (!empty($validated['analyses'])) {
                    foreach ($validated['analyses'] as $analyseCode) {
                        Analyse::create([
                            'laborantin_id' => $validated['laborantin_id'],
                            'consultation_id' => $consultation->id,
                            'centre_de_sante_id' => $centreId,
                            'type_analyse' => $analyseCode,
                            'statut' => 'prescrit', // valeur autorisée
                            'resultat' => '', // valeur par défaut vide
                            'date_analyse' => now(), // valeur par défaut actuelle
                        ]);
                    }
                }
            });

            return redirect()->back()->with('success' , 'Consultation terminée');
        }
}
