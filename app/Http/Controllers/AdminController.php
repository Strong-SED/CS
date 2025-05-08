<?php

namespace App\Http\Controllers;

use App\Models\CentreDeSante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{

    public function V_CentreDS(Request $request)
    {
        // Empêche l'accès si l'admin a déjà un centre
        if ($request->user()->centreDeSante) {
            return redirect()->route('AdminCS.Home');
        }

        return Inertia::render('Admin/CentreDS');
    }

    public function CS_store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
        ]);

        $validated['admin_c_s_id'] = Auth::user()->id;

        CentreDeSante::create($validated);

        return redirect()->route('AdminCS.Home')->with('success', 'Centre créé avec succès');
    }





    public function V_Dashboard()
    {
        $user = User::with('centreDeSante:id,id_centre_sante,nom')->get();

        return Inertia::render('Admin/Admin_Home', [
            'user' => $user
        ]);
    }

    public function V_create()
    {
        return inertia('Admin/Create');
    }
}
