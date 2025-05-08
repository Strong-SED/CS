<?php

namespace App\Http\Controllers;

use App\Mail\UserCredentialsMail;
use App\Models\CentreDeSante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

    public function CS_update(Request $request, CentreDeSante $centre)
    {
        $validated = $request->validate([
            'nom' => ['required'],
            'adresse' => ['required'],
            'ville' => ['required'],
        ]);

        $centre->update($validated);
        return redirect()->back()->with('success', 'Informations mises à jour');
    }





    public function V_Dashboard()
    {
        $centre = CentreDeSante::where('admin_c_s_id', Auth::user()->id)->firstOrFail();

        // Récupère tous les utilisateurs du même centre de santé
        $users = User::where('centre_de_sante_id', $centre->id)
            ->whereIn('role', ['secretaire', 'medecin', 'laborantin'])
            ->get();

        return Inertia::render('Admin/Admin_Home', [
            'users' => $users,
            'centreSante' => $centre
        ]);
    }

    public function V_create()
    {
        return inertia('Admin/Create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => ['required'],
                'prenom' => ['required'],
                'specialite' => $request->role === 'medecin' ? ['required'] : ['nullable'],
                'role' => ['required'],
                'email' => ['required', 'email', 'unique:users,email'],
            ]);

            $plainPassword = Str::random(8);
            $verificationToken = Str::random(60);

            $centre = CentreDeSante::where('admin_c_s_id', Auth::user()->id)->firstOrFail();

            User::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'specialite' => $validated['specialite'] ?? null,
                'role' => $validated['role'],
                'centre_de_sante_id' => $centre->id,
                'password' => Hash::make($plainPassword),
                'email_verification_token' => $verificationToken,
            ]);

            Mail::to($validated['email'])->send(
                new UserCredentialsMail(
                    $validated['nom'],
                    $validated['prenom'],
                    $validated['email'],
                    $plainPassword,
                    $verificationToken // Tu le passes dans le mail
                )
            );

            return redirect()->back()->with('success', 'Utilisateur enregistré');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request , User $user){
        try {
            $validated = $request->validate([
                'nom' => ['required'],
                'prenom' => ['required'],
                'specialite' => $request->role === 'medecin' ? ['required'] : ['nullable'],
                'role' => ['required'],
                'email' => ['required'],
            ]);

            $user->update($validated);
            return redirect()->back()->with('success' , 'Informations modifiées');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error' , 'Une erreur c\'est produite');
        }
    }


    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé');
    }
}
