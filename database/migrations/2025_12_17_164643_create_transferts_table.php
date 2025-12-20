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
        Schema::create('transferts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->date('date_transfert');
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->decimal('quantite', 10, 2);
            $table->foreignId('depot_source_id')->constrained('depots')->onDelete('cascade');
            $table->foreignId('depot_destination_id')->constrained('depots')->onDelete('cascade');
            $table->string('numero_vehicule')->nullable();
            $table->string('nom_chauffeur')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transferts');
    }
};
