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
        Schema::create('entrees_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entree_id')->constrained('entrees')->onDelete('cascade');
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('depot_id')->constrained('depots')->onDelete('restrict');
            $table->integer('stock');
            $table->decimal('prix_achat', 10, 2);
            $table->decimal('prix_total', 12, 2);
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrees_details');
    }
};
