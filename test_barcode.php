<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test de la méthode generateCodeBarre
echo "=== Test de génération de code-barres ===\n\n";

$year = date('Y');
$prefix = 'CB' . $year;
echo "Année: $year\n";
echo "Préfixe: $prefix\n\n";

// Récupérer le dernier code-barres de l'année en cours
$lastArticle = App\Models\Article::where('code_barre', 'LIKE', $prefix . '%')
    ->orderBy('code_barre', 'desc')
    ->first();

if ($lastArticle) {
    echo "Dernier article trouvé:\n";
    echo "  ID: {$lastArticle->id}\n";
    echo "  Code: {$lastArticle->code}\n";
    echo "  Code-barre: {$lastArticle->code_barre}\n";

    // Extraire le numéro séquentiel
    $lastNumber = intval(substr($lastArticle->code_barre, -6));
    echo "  Dernier numéro extrait: $lastNumber\n";
    echo "  Longueur du code-barre: " . strlen($lastArticle->code_barre) . "\n";
    echo "  Longueur du préfixe: " . strlen($prefix) . "\n";
    echo "  substr(\$lastArticle->code_barre, -6): " . substr($lastArticle->code_barre, -6) . "\n";
} else {
    echo "Aucun article trouvé avec le préfixe $prefix\n";
}

echo "\n=== Génération d'un nouveau code-barres ===\n";
$newCodeBarre = App\Models\Article::generateCodeBarre();
echo "Nouveau code-barres généré: $newCodeBarre\n";

// Vérifier tous les codes-barres existants
echo "\n=== Tous les codes-barres dans la DB ===\n";
$articles = App\Models\Article::whereNotNull('code_barre')
    ->orderBy('code_barre', 'desc')
    ->limit(10)
    ->get(['id', 'code', 'designation', 'code_barre']);

foreach ($articles as $article) {
    echo "ID: {$article->id} | Code: {$article->code} | Code-barre: {$article->code_barre}\n";
}
