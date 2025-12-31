<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class GenerateCodeBarreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Ce seeder génère des codes-barres pour tous les articles existants qui n'en ont pas
     */
    public function run(): void
    {
        // Récupérer tous les articles sans code-barres
        $articlesWithoutCodeBarre = Article::whereNull('code_barre')->get();

        $count = 0;

        foreach ($articlesWithoutCodeBarre as $article) {
            $article->code_barre = Article::generateCodeBarre();
            $article->save();
            $count++;

            $this->command->info("Code-barres généré pour l'article #{$article->id}: {$article->code_barre}");
        }

        $this->command->info("Total: {$count} code(s)-barres généré(s)");
    }
}
