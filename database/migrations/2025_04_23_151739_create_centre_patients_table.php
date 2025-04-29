<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('centre_patient', function (Blueprint $table) {
            // Clés étrangères
            $table->foreignId('centre_de_sante_id')->constrained('centre_de_santes');
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('created_by_user_id')->constrained('users'); // Secrétaire qui a fait l'enregistrement

            // Métadonnées (optionnel)
            $table->date('date_enregistrement')->default(now());

            // Clé primaire composite
            $table->primary(['centre_de_sante_id', 'patient_id']);

            // Index
            $table->index('created_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centre_patients');
    }
};
