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
        Schema::create('analyses', function (Blueprint $table) {
            $table->id();

            // Clés étrangères
            $table->foreignId('laborantin_id')
                  ->constrained('users')
                  ->onDelete('set null');

            $table->foreignId('consultation_id')
                  ->constrained('consultations')
                  ->cascadeOnDelete();

            $table->foreignId('centre_de_sante_id')
                  ->constrained('centre_de_santes')
                  ->cascadeOnDelete();

            // Données de l'analyse
            $table->string('type_analyse');
            $table->text('resultat');
            $table->dateTime('date_analyse');
            $table->enum('statut', ['prescrit','en_cours', 'termine'])->default('prescrit');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('type_analyse');
            $table->index('date_analyse');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyses');
    }
};
