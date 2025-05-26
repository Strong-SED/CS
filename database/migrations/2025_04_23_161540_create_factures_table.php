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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();

            // Clés étrangères
            $table->foreignId('patient_id')
                  ->constrained('patients')
                  ->cascadeOnDelete();

            $table->foreignId('secretaire_id')
                  ->constrained('users') // Secrétaire est dans la table users
                  ->cascadeOnDelete();

            $table->foreignId('centre_de_sante_id')
                  ->constrained('centre_de_santes')
                  ->cascadeOnDelete();

            // Données de la facture
            $table->string('numero_facture')->unique();
            $table->date('date_emission');
            $table->decimal('montant', 10, 2);
            $table->enum('statut', ['impaye' ,'paye'])->default('impaye');
            $table->json('details')->nullable();    


            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('numero_facture');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
