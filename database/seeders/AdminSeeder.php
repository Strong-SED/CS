<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'nom' => 'Admin',
            'prenom' => 'John',
            'email' => 'adminCS@gmail.com',
            'password' => Hash::make('admin123'), // ⚠️ Utilise un mot de passe plus sécurisé en production
            'role' => 'admin_cs',
            'specialite' => null, // Peut rester nullable
            'email_verified_at' => now(), // Marque l'email comme vérifié immédiatement
            'centre_de_sante_id' => null, // Rattachement éventuel à un centre
        ]);
    }
}
