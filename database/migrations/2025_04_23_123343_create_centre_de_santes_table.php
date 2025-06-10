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

            // Clé étrangère vers l'AdminCS responsable
            $table->unsignedBigInteger('admin_c_s_id')->nullable()->unique();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('admin_c_s_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Index
            $table->index('ville');
            $table->index('admin_c_s_id');

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
