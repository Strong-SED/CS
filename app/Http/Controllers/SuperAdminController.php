<?php

namespace App\Http\Controllers;

use App\Mail\AdminCredentialsMail;
use App\Models\AdminCS;
use App\Models\SuperAdmin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SuperAdminController extends Controller
{
    //
    public function V_Dashboard()
    {
        // Récupérer les AdminCS avec leurs Centres de Santé associés
        $admins = AdminCS::with('centreDeSante:id,nom,admin_c_s_id')->get();

        // Transformer les données pour le frontend
        $formattedAdmins = $admins->map(function ($admin) {
            return [
                'id' => $admin->id,
                'nom' => $admin->nom,
                'prenom' => $admin->prenom,
                'email' => $admin->email,
                'centre_sante' => $admin->centreDeSante ? $admin->centreDeSante->nom : 'Pas de centre de santé à son actif',
                // Ajoutez l'objet complet si nécessaire
                'centreDeSante' => $admin->centreDeSante
            ];
        });

        return inertia('SuperAdmin/Home', [
            'admin' => $formattedAdmins
        ]);
    }



    public function V_create_admin()
    {
        return inertia('SuperAdmin/Create');
    }

    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required'],
            'prenom' => ['required'],
            'email' => ['required', 'email'],
        ]);

        try {
            $plainPassword = Str::random(8);
            $verificationToken = Str::random(60); // Token unique

            AdminCS::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'password' => Hash::make($plainPassword),
                'email_verification_token' => $verificationToken,
            ]);

            Mail::to($validated['email'])->send(
                new AdminCredentialsMail(
                    $validated['nom'],
                    $validated['prenom'],
                    $validated['email'],
                    $plainPassword,
                    $verificationToken // Tu le passes dans le mail
                )
            );

            return redirect()->back()->with('success', 'Admin enregistré');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue');
        }
    }


    // Méthode pour la mise à jour
    public function update(Request $request, AdminCS $admin)
    {
        $validated = $request->validate([
            'nom' => ['required'],
            'prenom' => ['required'],
            'email' => ['required', 'email'],
        ]);

        $admin->update($validated);

        return redirect()->back()->with('success', 'Administrateur mis à jour avec succès');
    }

    // Méthode pour la suppression
    public function destroy(AdminCS $admin)
    {
        $admin->delete();

        return redirect()->back()->with('success', 'Administrateur supprimé avec succès');
    }
}
