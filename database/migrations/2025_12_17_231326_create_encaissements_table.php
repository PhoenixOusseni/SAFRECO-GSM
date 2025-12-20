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
        Schema::create('encaissements', function (Blueprint $table) {
            $table->id();
            $table->date('date_encaissement');
            $table->decimal('montant', 15, 2);
            $table->decimal('montant_encaisse', 15, 2);
            $table->decimal('reste', 15, 2);
            $table->text('mode_paiement')->nullable();

            $table->foreignId('vente_id')->constrained('ventes')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encaissements');
    }
};
