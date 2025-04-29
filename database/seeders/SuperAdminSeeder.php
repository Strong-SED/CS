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
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password123'), // ⚠️ Tu peux changer ça
            'role' => 'super_admin',
            'specialite' => null,
            'centre_de_sante_id' => null, // Pas rattaché à un centre
        ]);
    }
}
