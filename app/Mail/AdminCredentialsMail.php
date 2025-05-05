<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nom;
    public $prenom;
    public $email;
    public $password;
    public $loginUrl;
    public $verificationToken;

    /**
     * Create a new message instance.
     */
    public function __construct($nom, $prenom, $email, $password, $verificationToken)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->loginUrl = route("verifyToken", $verificationToken); // ou verifyEmail
        $this->verificationToken = $verificationToken;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Vos identifiants d\'administration')
            ->markdown('emails.admin-credentials')
            ->with([
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' => $this->email,
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
                'verificationToken' => $this->verificationToken,
            ]);
    }
}
