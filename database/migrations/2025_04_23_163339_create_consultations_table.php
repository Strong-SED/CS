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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            // Clés étrangères essentielles
            $table->foreignId('dossier_medical_id')->constrained('dossier_medicals')->cascadeOnDelete();
            $table->foreignId('medecin_id')->constrained('users');

            // Données de la consultation
            $table->dateTime('date_consultation');
            $table->text('motif');
            $table->text('diagnostic');
            $table->text('traitement_prescrit');
            $table->text('observations')->nullable();

            // Champ analyses modifié pour utiliser une enum
            $table->json('analyses')->nullable()->comment('Liste des analyses prescrites');

            // Données cliniques
            $table->string('poids')->nullable();
            $table->string('taille')->nullable();
            $table->string('temperature')->nullable();
            $table->string('tension_arterielle')->nullable();

            // Métadonnées
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('date_consultation');
            $table->index(['dossier_medical_id', 'medecin_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
