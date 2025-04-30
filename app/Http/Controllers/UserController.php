<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    //
    public function V_Login()
    {
        return inertia('Auth/Login');
    }

    public function login_verifier(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Veuillez entrer votre adresse e-mail.',
            'email.email' => 'L\'adresse e-mail est invalide.',
            'password.required' => 'Veuillez entrer votre mot de passe.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Le middleware RedirectByRole gérera la redirection
            return redirect()->route('role.redirect');
        }

        return back()->withErrors([
            'email' => 'Les identifiants sont incorrects.',
            'password' => 'Mot de passe incorrect.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location(route('login'));
    }
}
