<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    // View pour la connexion
    public function V_Login()
    {
        return inertia('Auth/Login');
    }

    // Vérification des identifiants de connexion
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


    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Lien de vérification invalide.',
            ]);
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return redirect()->route('login')->with('msg', 'Adresse e-mail vérifiée. Vous pouvez vous connecter.')->with('success', true);
    }



    // Déconnexion de l'utilisateur
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location(route('login'));
    }

    // View pour la l'identification de l'utilisateur pour la réinitialisation du mot de passe
    public function V_forgot_password()
    {
        return inertia('Auth/ForgotPassword');
    }

    // Vérification de l'identité de l'utilisateur pour la réinitialisation du mot de passe
    public function forgot_password_verifier(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email']
        ], [
            'email.email' => 'L\'adresse e-mail est invalide.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Envoi d'un e-mail personnalisé à l'utilisateur
            Mail::to($user->email)->send(new PasswordResetMail($user));
            return back()->with('status', 'Un e-mail de réinitialisation a été envoyé.');
        }

        return back()->withErrors([
            'email' => 'Aucun utilisateur trouvé avec cette adresse e-mail.',
        ])->onlyInput('email');
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function showProfile()
    {
        // Récupère l'utilisateur authentifié.
        // Si vous avez chargé des relations spécifiques (ex: centreDeSante) sur l'utilisateur
        // via votre middleware Inertia, elles seront automatiquement disponibles.
        // Sinon, vous pourriez les charger ici si elles sont requises pour l'affichage (ex: Auth::user()->load('centreDeSante'))
        $user = Auth::user()->load('centreDeSante');

        return Inertia::render('Auth/Profil', [
            'user' => $user, // Passe l'objet utilisateur complet à la vue
        ]);
    }

    public function showPrivacyPolicy()
    {
        return Inertia::render('PrivacyPolicy');
    }
}
