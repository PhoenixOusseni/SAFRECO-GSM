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
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('depot_id')->constrained('depots')->onDelete('cascade');
            $table->enum('type_mouvement', ['entree', 'sortie'])->comment('Type de mouvement');
            $table->string('numero_document')->nullable()->comment('Numéro du document (entree ou sortie)');
            $table->decimal('quantite', 10, 2)->comment('Quantité du mouvement (positive pour entrée, négative pour sortie)');
            $table->decimal('quantite_avant', 10, 2)->default(0)->comment('Quantité disponible avant le mouvement');
            $table->decimal('quantite_apres', 10, 2)->default(0)->comment('Quantité disponible après le mouvement');
            $table->decimal('prix_unitaire', 10, 2)->nullable()->comment('Prix unitaire au moment du mouvement');
            $table->string('reference_type')->nullable()->comment('Type de référence: entree, sortie');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('ID de la référence (entree_id ou sortie_id)');
            $table->text('observations')->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index(['article_id', 'depot_id', 'created_at']);
            $table->index('type_mouvement');
            $table->index('numero_document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};
