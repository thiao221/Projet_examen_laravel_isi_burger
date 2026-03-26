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
        Schema::table('users', function (Blueprint $table) {
            // On ajoute la colonne role après la colonne email
            // Deux valeurs possibles : client ou gestionnaire
            // Par défaut tout nouvel utilisateur est un client
            $table->enum('role', ['client', 'gestionnaire'])
                ->default('client')
                ->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(  'users', function (Blueprint $table) {
            // Si on annule la migration, on supprime la colonne
            $table->dropColumn('role');
        });
    }
};
