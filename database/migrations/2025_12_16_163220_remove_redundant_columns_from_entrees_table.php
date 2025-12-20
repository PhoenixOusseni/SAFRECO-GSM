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
        Schema::table('entrees', function (Blueprint $table) {
            // Supprimer les colonnes redondantes depot_id et article_id
            // car les vraies données sont dans la table entrees_details
            $table->dropForeign(['depot_id']);
            $table->dropForeign(['article_id']);
            $table->dropColumn(['depot_id', 'article_id']);

            // Renommer numero_bon en numero_facture pour cohérence avec le modèle
            $table->renameColumn('numero_bon', 'numero_facture');

            // Ajouter numero_entree pour traçabilité
            $table->string('numero_entree')->unique()->after('code');

            // Supprimer la colonne code qui fait doublon avec numero_entree
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entrees', function (Blueprint $table) {
            // Remettre la colonne code
            $table->string('code')->unique();

            // Supprimer numero_entree
            $table->dropUnique(['numero_entree']);
            $table->dropColumn('numero_entree');

            // Renommer numero_facture en numero_bon
            $table->renameColumn('numero_facture', 'numero_bon');

            // Remettre les colonnes depot_id et article_id
            $table->foreignId('depot_id')->nullable()->constrained('depots')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('article_id')->nullable()->constrained('articles')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
