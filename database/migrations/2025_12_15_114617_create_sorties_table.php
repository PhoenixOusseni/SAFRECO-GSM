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
        Schema::create('sorties', function (Blueprint $table) {
            $table->id();
            $table->string('numero_sortie')->unique();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->date('date_sortie');
            $table->string('numero_facture')->nullable();
            $table->enum('type_sortie', ['vente', 'transfert', 'destruction', 'inventaire'])->default('vente');
            $table->decimal('montant_total', 12, 2)->default(0);
            $table->text('observations')->nullable();
            $table->enum('statut', ['validee', 'en_attente', 'rejetee'])->default('validee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestions_sorties');
    }
};
