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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_vente')->unique();
            $table->date('date_vente');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('numero_vehicule')->nullable();
            $table->string('chauffeur')->nullable();
            $table->decimal('montant_total', 15, 2)->default(0);
            //$table->enum('statut', ['brouillon', 'encaisse'])->default('brouillon');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
