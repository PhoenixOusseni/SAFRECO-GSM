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
        Schema::create('encaissement_soldes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('encaissement_id');
            $table->decimal('montant', 15, 2);
            $table->string('mode_paiement')->nullable();
            $table->date('date_solde');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('encaissement_id')
                  ->references('id')
                  ->on('encaissements')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encaissement_soldes');
    }
};
