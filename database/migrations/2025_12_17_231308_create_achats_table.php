<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
    */
    public function up(): void
    {
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->string('numero_achat')->unique();
            $table->string('designation')->nullable();
            $table->date('date_achat');
            $table->decimal('montant_total', 12, 2)->default(0);
            $table->enum('statut', ['brouillon', 'valide'])->default('brouillon');

            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};

