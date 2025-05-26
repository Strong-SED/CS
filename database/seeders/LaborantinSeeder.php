<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LaborantinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'nom' => 'DUPONT',
            'prenom' => 'Stéphane',
            'email' => 'laborantin@gmail.com',
            'password' => Hash::make('labo123'), // ⚠️ Utilise un mot de passe plus sécurisé en production
            'role' => 'laborantin',
            'specialite' => null, // Peut rester nullable
            'email_verified_at' => now(), // Marque l'email comme vérifié immédiatement
            'centre_de_sante_id' => 1, // Rattachement éventuel à un centre
        ]);
    }
}
