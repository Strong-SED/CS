<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
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
        $query = Patient::query();

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
}
