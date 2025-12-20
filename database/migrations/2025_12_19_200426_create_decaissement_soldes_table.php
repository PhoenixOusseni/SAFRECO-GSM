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
        Schema::create('decaissement_soldes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('decaissement_id')->constrained('decaissements')->onDelete('cascade');
            $table->date('date_solde');
            $table->decimal('montant_solde', 15, 2);
            $table->decimal('solde_cumule', 15, 2);
            $table->string('mode_paiement')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decaissement_soldes');
    }
};
