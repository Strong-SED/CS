<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RendezVousController extends Controller
{
    //
    public function Rdv_V()
    {
        $centreId = Auth::user()->centreDeSante->id;

        // Récupérer tous les rendez-vous qui appartiennent à ce centre de santé.
        // On charge les relations 'patient' et 'medecin' pour pouvoir afficher
        // les noms du patient et du médecin dans la vue.
        $rdvs = RendezVous::where('centre_de_sante_id', $centreId)
            ->with('patient', 'medecin')
            ->orderBy('date_heure', 'asc') // Optionnel: trier par date et heure croissantes
            ->get();

        // Récupérer tous les patients (si nécessaire pour d'autres fonctionnalités, ex: création de RDV qui n'est plus le cas pour la secrétaire)
        $patients = Patient::all();

        // Retourner la vue Inertia avec les données
        return Inertia::render('Secretaire/RDV', [
            'rdvs' => $rdvs,
            'patients' => $patients, // Peut être retiré si non utilisé dans la vue pour la secrétaire
            'auth' => [
                'user' => Auth::user(), // Informations de l'utilisateur authentifié
            ],
            // 'flash' => session()->get('flash'), // Récupère les messages flash de la session
        ]);
    }


    public function update_sec(Request $request, RendezVous $rendezVou)
    {
        $request->validate([
            'etat' => 'required|string|in:planifié,respecté,annulé,reporté',
            'date_heure' => 'nullable|date', // Rendre nullable si le report n'est pas toujours avec une nouvelle date
            'motif_report' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'etat' => $request->etat,
        ];

        // Mettre à jour la date_heure si elle est fournie (pour le report)
        if ($request->filled('date_heure')) {
            $updateData['date_heure'] = $request->date_heure;
        }

        // Mettre à jour le motif de report si fourni
        if ($request->filled('motif_report')) {
            $updateData['motif_report'] = $request->motif_report; // Assurez-vous que votre colonne est 'motif' ou adaptez
        } elseif ($request->etat === 'respecté' && $request->filled('notes')) {
            $updateData['notes'] = $request->notes;
        }


        $rendezVou->update($updateData);

        // Rediriger avec un message flash de succès
        return redirect()->back()->with('flash', ['success' => 'Rendez-vous mis à jour avec succès.']);
    }










    public function Rdv_VM()
    {
        // Récupérer l'ID du médecin authentifié
        $medecin = Auth::user();

        // Récupérer les rendez-vous du médecin connecté, avec les informations du patient
        $rendezVous = RendezVous::where('medecin_id', $medecin->id)
            ->with('patient')
            ->get();

        // Récupérer les patients qui ont eu des consultations avec ce médecin
        $patients = Patient::whereHas('dossierMedical.consultations', function ($query) use ($medecin) {
            $query->where('medecin_id', $medecin->id);
        })
            ->distinct() // Pour éviter les doublons si un patient a plusieurs consultations
            ->get();


        return Inertia::render('Medecin/RDV', [
            'rdvs' => $rendezVous,
            'patients' => $patients,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'date_heure' => ['required', 'date', 'after_or_equal:now'], // 'after_or_equal:now' pour ne pas planifier dans le passé
            'motif' => ['required', 'string', 'max:255'],
            // 'medecin_id' et 'centre_de_sante_id' seront ajoutés automatiquement ou via l'utilisateur authentifié
        ]);

        // Assurez-vous que le médecin est connecté
        $medecin = Auth::user();

        // Créer le rendez-vous
        RendezVous::create([
            'patient_id' => $validatedData['patient_id'],
            'medecin_id' => $medecin->id,
            'centre_de_sante_id' => $medecin->centre_de_sante_id, // Assurez-vous que le médecin a un centre_de_sante_id, sinon définir une valeur par défaut
            'date_heure' => $validatedData['date_heure'],
            'motif' => $validatedData['motif'],
            'etat' => 'planifié', // L'état par défaut est 'planifié'
        ]);

        return redirect()->back()
            ->with('success', 'Rendez-vous ajouté avec succès.');
    }

    public function update(Request $request, RendezVous $rendezVous)
    {
        // Validez uniquement les champs qui sont censés être mis à jour
        $validatedData = $request->validate([
            'etat' => ['sometimes', 'required', 'in:planifié,respecté,annulé,reporté'],
            'date_heure' => ['nullable', 'date', 'after_or_equal:now'],
            'motif_report' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        // Mettre à jour l'état et les autres champs
        $rendezVous->fill($validatedData);

        // Logique spécifique pour le report
        if ($request->input('etat') === 'reporté') {
            $rendezVous->reporté_le = now();
        } else {
            // Réinitialiser motif_report et reporté_le si l'état change
            if ($rendezVous->isDirty('etat') && $rendezVous->getOriginal('etat') === 'reporté') {
                $rendezVous->motif_report = null;
                $rendezVous->reporté_le = null;
            }
        }

        $rendezVous->save();

        return redirect()->back()
            ->with('success', 'Rendez-vous mis à jour avec succès.');
    }
}
