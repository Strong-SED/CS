<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SecretaireController extends Controller
{
    //
    public function Home()
    {
        return Inertia::render('Secretaire/Home');
    }


    public function V_Dashboard()
    {
        return Inertia::render('Secretaire/Dashboard');
    }


    public function V_CreateP(Request $request)
    {
        $query = Patient::with('dossierMedical')
            ->where('status', 'actif') // Ne montre que les patients actifs
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

        // Filtre par genre (remplace le filtre par statut)
        if ($request->has('genre') && !empty($request->genre)) {
            $query->where('genre', $request->genre);
        }

        $patients = $query->paginate(6)->withQueryString();

        return Inertia::render('Secretaire/CreateP', [
            'patients' => $patients,
            'filters' => $request->only(['search', 'genre']) // Changé de status à genre
        ]);
    }

    public function store(Request $request)
    {
        $centreId = Auth::user()->centreDeSante->id;
        $request->merge(['centre_de_sante_id' => $centreId]);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'genre' => 'required|in:M,F',
            'email' => 'nullable|email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'npi' => 'nullable|string|max:50',
            'status' => 'required|in:actif,inactif',
            'centre_de_sante_id' => 'required|exists:centre_de_santes,id'
        ]);

        try {
            // Recherche d'un patient existant
            $patient = Patient::where(function ($query) use ($validated) {
                $query->where('nom', $validated['nom'])
                    ->where('prenom', $validated['prenom'])
                    ->where('date_naissance', $validated['date_naissance']);
            })
                ->when($validated['email'], function ($query, $email) {
                    return $query->orWhere('email', $email);
                })
                ->when($validated['npi'], function ($query, $npi) {
                    return $query->orWhere('npi', $npi);
                })
                ->when($validated['telephone'], function ($query, $telephone) {
                    return $query->orWhere('telephone', $telephone);
                })
                ->first();

            if ($patient) {
                // Si le patient est inactif, on le réactive
                if ($patient->status === 'inactif') {
                    $patient->status = 'actif';
                    $patient->save();
                }

                // Vérifier s'il est déjà dans ce centre
                $exists = $patient->centres()
                    ->where('centre_de_sante_id', $validated['centre_de_sante_id'])
                    ->exists();

                if ($exists) {
                    return redirect()->route('Secretaire.CreateP')
                        ->with('warning', 'Ce patient est déjà enregistré dans ce centre.')
                        ->with('patient', $patient);
                } else {
                    // Ajouter au nouveau centre
                    $patient->centres()->attach($validated['centre_de_sante_id'], [
                        'created_by_user_id' => Auth::id(),
                        'date_enregistrement' => now()
                    ]);

                    return redirect()->route('Secretaire.CreateP')
                        ->with('success', 'Patient réactivé et ajouté au centre avec succès.')
                        ->with('patient', $patient);
                }
            } else {
                // Créer un nouveau patient
                $patient = Patient::create($validated);

                // Lier au centre
                $patient->centres()->attach($validated['centre_de_sante_id'], [
                    'created_by_user_id' => Auth::id(),
                    'date_enregistrement' => now()
                ]);

                return redirect()->route('Secretaire.CreateP')
                    ->with('success', 'Nouveau patient enregistré avec succès.')
                    ->with('patient', $patient);
            }
        } catch (Exception $e) {
            return redirect()->route('Secretaire.CreateP')
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    // Mettre à jour un patient
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'genre' => 'required|in:M,F',
            'email' => 'nullable|email|unique:patients,email,' . $id,
            'telephone' => 'required|string|max:20|unique:patients,telephone,' . $id,
            'adresse' => 'nullable|string',
            'npi' => 'nullable|string|max:50|unique:patients,npi,' . $id,
            'status' => 'sometimes|required' // Ajouté si le statut peut être modifié
        ]);

        try {
            // Vérification des champs uniques avant mise à jour
            $existingPatient = Patient::where(function ($query) use ($validated, $id) {
                $query->where('nom', $validated['nom'])
                    ->where('prenom', $validated['prenom'])
                    ->where('date_naissance', $validated['date_naissance'])
                    ->where('id', '!=', $id);
            })
                ->when($validated['email'], function ($query, $email) use ($id) {
                    return $query->orWhere('email', $email)->where('id', '!=', $id);
                })
                ->when($validated['npi'], function ($query, $npi) use ($id) {
                    return $query->orWhere('npi', $npi)->where('id', '!=', $id);
                })
                ->when($validated['telephone'], function ($query, $telephone) use ($id) {
                    return $query->orWhere('telephone', $telephone)->where('id', '!=', $id);
                })
                ->first();

            if ($existingPatient) {
                return redirect()->route('Secretaire.CreateP')
                    ->with('warning', 'Un patient avec des informations similaires existe déjà (ID: ' . $existingPatient->id . ')');
            }

            $patient->update($validated);

            return redirect()->route('Secretaire.CreateP')
                ->with('success', 'Patient mis à jour avec succès');
        } catch (Exception $e) {
            return redirect()->route('Secretaire.CreateP')
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    // Supprimer un patient
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient["status"] = "inactif";
        $patient->save();

        return redirect()->back()
            ->with('success', 'Patient supprimé avec succès');
    }
}
