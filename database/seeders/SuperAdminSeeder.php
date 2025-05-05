<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nom' => 'Super',
            'prenom' => 'Admin',
            'email' => 'staylegyver@gmail.com',
            'password' => Hash::make('password123'), // ⚠️ Utilise un mot de passe plus sécurisé en production
            'role' => 'super_admin',
            'specialite' => null, // Peut rester nullable
            'email_verified_at' => now(), // Marque l'email comme vérifié immédiatement
            'centre_de_sante_id' => null, // Rattachement éventuel à un centre
        ]);

    }
}
