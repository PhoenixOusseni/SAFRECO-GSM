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
        Schema::create('achat_details', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('achat_id')->constrained('achats')->onDelete('cascade');
            // $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            // $table->decimal('quantite', 10, 2);
            // $table->decimal('prix_unitaire', 15, 2);
            // $table->decimal('prix_total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achat_details');
    }
};
