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
        Schema::create('inventaires', function (Blueprint $table) {
            $table->id();
            $table->string('numero_inventaire')->unique();
            $table->date('date_inventaire');
            $table->foreignId('depot_id')->nullable()->constrained('depots')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('statut', ['en_cours', 'valide', 'annule'])->default('en_cours');
            $table->text('observation')->nullable();
            $table->decimal('ecart_total_valeur', 15, 2)->default(0);
            $table->integer('ecart_total_quantite')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaires');
    }
};
