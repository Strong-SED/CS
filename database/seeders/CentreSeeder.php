<?php

namespace Database\Seeders;

use App\Models\CentreDeSante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CentreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CentreDeSante::create([
            'admin_c_s_id' => 2,
            'nom' => 'Centre de santé Aviennes',
            'adresse' => 'Rue 240, Cadjehoun',
            'ville' => "Cotonou"
        ]);
    }
}
