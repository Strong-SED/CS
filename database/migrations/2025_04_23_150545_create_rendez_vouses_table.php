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
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();

            // Clés étrangères
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('medecin_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('centre_de_sante_id')->constrained('centre_de_santes')->cascadeOnDelete();

            // Données du rendez-vous
            $table->dateTime('date_heure');
            $table->string('motif');

            // Etat
            $table->enum('etat', [
                'planifié',
                'respecté',
                'annulé',
                'reporté',
            ])->default('planifié');

            // Gestion des annulations
            $table->string('motif_report')->nullable();
            $table->timestamp('reporté_le')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('date_heure');
            $table->index('etat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};
