<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SecretaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'nom' => 'Secretaire',
            'prenom' => 'Lucy',
            'email' => 'secretaire@gmail.com',
            'password' => Hash::make('secret123'), // ⚠️ Utilise un mot de passe plus sécurisé en production
            'role' => 'secretaire',
            'specialite' => null, // Peut rester nullable
            'email_verified_at' => now(), // Marque l'email comme vérifié immédiatement
            'centre_de_sante_id' => 1, // Rattachement éventuel à un centre
        ]);
    }
}
