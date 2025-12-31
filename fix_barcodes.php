<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Correction des codes-barres existants ===\n\n";

// Récupérer tous les articles avec des codes-barres
$articles = App\Models\Article::whereNotNull('code_barre')
    ->orderBy('id')
    ->get();

echo "Nombre d'articles avec code-barres: " . $articles->count() . "\n\n";

$year = date('Y');
$prefix = 'CB' . $year;
$counter = 1;

foreach ($articles as $article) {
    $oldCodeBarre = $article->code_barre;
    $newCodeBarre = $prefix . str_pad($counter, 6, '0', STR_PAD_LEFT);

    if ($oldCodeBarre !== $newCodeBarre) {
        echo "Article #{$article->id} ({$article->designation})\n";
        echo "  Ancien: $oldCodeBarre\n";
        echo "  Nouveau: $newCodeBarre\n";

        $article->code_barre = $newCodeBarre;
        $article->save();

        echo "  ✓ Corrigé\n\n";
    } else {
        echo "Article #{$article->id} OK: $newCodeBarre\n";
    }

    $counter++;
}

echo "\n=== Correction terminée ===\n";
echo "Test de génération d'un nouveau code-barres:\n";
$newCode = App\Models\Article::generateCodeBarre();
echo "Prochain code-barres: $newCode\n";
