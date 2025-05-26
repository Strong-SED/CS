<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\DossierMedical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DossierMedicalController extends Controller
{
    //
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'groupe_sanguin' => 'nullable|string|max:10',
            'allergies' => 'nullable|string|max:500',
            'historique_medical' => 'nullable|string|max:1000',
            'antecedents_medicaux' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier si un dossier existe déjà
        $existingDossier = DossierMedical::where('patient_id', $request->patient_id)->first();

        if ($existingDossier) {
            return redirect()->back()
                ->with('error', 'Un dossier médical existe déjà pour ce patient.');
        }

        $dossier = DossierMedical::create($validator->validated());

        return redirect()->back()
            ->with('success', 'Dossier médical créé avec succès.');
    }


}
