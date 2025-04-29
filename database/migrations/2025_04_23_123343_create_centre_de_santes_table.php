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
        Schema::create('centre_de_santes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('adresse');
            $table->string('ville');

            // Clé étrangère vers l'Admin CS créateur (un centre a UN créateur)
            $table->unsignedBigInteger('created_by_user_id')->unique(); // Un centre n'a qu'un seul admin CS responsable

            $table->softDeletes();
            $table->timestamps();

            // Contrainte : created_by_user_id doit référencer un user avec role = admin_cs
            // Note: MySQL/PostgreSQL supportent les CHECK constraints, mais pas les WHERE dans les FK.
            $table->foreign('created_by_user_id')
                  ->references('id')
                  ->on('users');
                  // ->where('role', User::ROLE_ADMIN_CS); // Non supporté directement en SQL pur

            // Index pour les performances
            $table->index('ville');
            $table->index('created_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centre_de_santes');
    }
};
