<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MedecinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'nom' => 'NGOKO',
            'prenom' => 'Patrick',
            'email' => 'medecin@gmail.com',
            'password' => Hash::make('medecin123'), // ⚠️ Utilise un mot de passe plus sécurisé en production
            'role' => 'medecin',
            'specialite' => 'Généraliste', // Peut rester nullable
            'email_verified_at' => now(), // Marque l'email comme vérifié immédiatement
            'centre_de_sante_id' => 1, // Rattachement éventuel à un centre
        ]);

        User::create([
            'nom' => 'TCHEGOUN',
            'prenom' => 'Arnold',
            'email' => 'arnoldtcheg@gmail.com',
            'password' => Hash::make('tchegmed123'), // ⚠️ Utilise un mot de passe plus sécurisé en production
            'role' => 'medecin',
            'specialite' => 'Généraliste', // Peut rester nullable
            'email_verified_at' => now(), // Marque l'email comme vérifié immédiatement
            'centre_de_sante_id' => 2, // Rattachement éventuel à un centre
        ]);
    }
}
