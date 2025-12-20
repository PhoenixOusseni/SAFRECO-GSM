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
        Schema::table('decaissements', function (Blueprint $table) {
            // Ajouter la colonne achat_id si elle n'existe pas
            if (!Schema::hasColumn('decaissements', 'achat_id')) {
                $table->foreignId('achat_id')->nullable()->constrained('achats')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('decaissements', function (Blueprint $table) {
            $table->dropForeignIdFor('achats', 'achat_id');
            $table->dropColumn('achat_id');
        });
    }
};
