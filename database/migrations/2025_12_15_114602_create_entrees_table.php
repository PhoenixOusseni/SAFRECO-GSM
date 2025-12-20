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
        Schema::create('entrees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('depot_id')->nullable()->constrained('depots')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('article_id')->nullable()->constrained('articles')->onDelete('cascade')->onUpdate('cascade');

            $table->date('date_entree');
            $table->string('numero_bon')->nullable();
            $table->decimal('montant_total', 12, 2)->default(0);
            $table->text('observations')->nullable();
            $table->enum('statut', ['recu', 'en_attente', 'rejete'])->default('recu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrees');
    }
};
