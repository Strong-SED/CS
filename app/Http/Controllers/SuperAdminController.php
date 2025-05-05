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
        $admin = AdminCS::all();

        return inertia('SuperAdmin/Home', [
            'admin' => $admin
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

            return redirect()
                ->back()
                ->with('msg', 'Administrateur enregistré. Un email avec le mot de passe temporaire a été envoyé.')
                ->with('success', true);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('msg', 'Erreur: ' . $e->getMessage())
                ->with('error', true);
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

        return redirect()->back()->with('msg', 'Administrateur mis à jour avec succès')->with('success', true);
    }

    // Méthode pour la suppression
    public function destroy(AdminCS $admin)
    {
        $admin->delete();

        return redirect()->back()->with('msg', 'Administrateur supprimé avec succès')->with('success', true);
    }
}
