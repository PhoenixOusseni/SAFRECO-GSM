<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::all();
        return view('pages.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Création de l'article
        $article = new Article();
        $code = 'ART-' . str_pad(Article::max('id') + 1, 5, '0', STR_PAD_LEFT);
        $article->code = $code;
        $article->designation = $request->designation;
        $article->reference = $request->reference;
        $article->prix_achat = $request->prix_achat;
        $article->prix_vente = $request->prix_vente;
        $article->seuil = $request->seuil; // Seuil de réapprovisionnement par défaut à 10
        $article->stock = 0; // Stock initial à 0

        // Génération automatique du code-barres
        $article->code_barre = Article::generateCodeBarre();
        
        $article->save();

        // Redirection avec un message de succès
        return redirect()->route('gestions_articles.index')->with('success', 'Article ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $articleFinds = Article::findOrFail($id);
        return view('pages.articles.edit', compact('articleFinds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // Récupération de l'article à mettre à jour
        $article = Article::findOrFail($id);
        // Mise à jour des champs
        $article->designation = $request->designation;
        $article->reference = $request->reference;
        $article->prix_achat = $request->prix_achat;
        $article->prix_vente = $request->prix_vente;
        $article->seuil = $request->seuil;
        $article->stock = $request->stock;
        // Sauvegarde des modifications
        $article->save();
        // Redirection avec un message de succès
        return redirect()->route('gestions_articles.index')->with('success', 'Article mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return redirect()->route('gestions_articles.index')->with('success', 'Article supprimé avec succès.');
    }

    /**
     * Import articles from CSV/Excel file
     */
    public function import(Request $request)
    {
        try {
            // Validation du fichier
            $request->validate([
                'file' => 'required|file|mimes:csv,txt|max:2048'
            ]);

            $file = $request->file('file');

            // Ouvrir le fichier CSV
            $handle = fopen($file->getRealPath(), 'r');

            // Détecter le séparateur (virgule ou point-virgule)
            $firstLine = fgets($handle);
            rewind($handle);

            $delimiter = ',';
            if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                $delimiter = ';';
            }

            // Ignorer la première ligne (en-têtes)
            $header = fgetcsv($handle, 1000, $delimiter);

            $imported = 0;
            $errors = [];

            // Obtenir le dernier ID pour la génération des codes
            $lastId = Article::max('id') ?? 0;

            // Parcourir chaque ligne du fichier
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                // Ignorer les lignes complètement vides (toutes les colonnes vides)
                $hasData = false;
                foreach ($row as $cell) {
                    if (!empty(trim($cell))) {
                        $hasData = true;
                        break;
                    }
                }

                if (!$hasData) {
                    continue;
                }

                try {
                    // Mapper les colonnes du CSV
                    // Format attendu: Type, Raison Sociale, Nom, Adresse, Téléphone, Email, Ville
                    $designation = trim($row[0] ?? '');
                    $reference = trim($row[1] ?? '');
                    $prix_achat = floatval(str_replace(',', '.', trim($row[2] ?? '0')));
                    $prix_vente = floatval(str_replace(',', '.', trim($row[3] ?? '0')));

                    // Debug: Log des données lues (à retirer en production)
                    \Log::info('Import ligne', [
                        'row' => $row,
                        'designation' => $designation,
                        'reference' => $reference,
                        'prix_achat' => $prix_achat,
                        'prix_vente' => $prix_vente,
                    ]);

                    // Générer un code automatique unique
                    $lastId++;
                    $code = 'ART-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);

                    // Vérifier si le code existe déjà (sécurité)
                    while (Article::where('code', $code)->exists()) {
                        $lastId++;
                        $code = 'ART-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);
                    }

                    // Créer un nouveau article
                    Article::create([
                        'code' => $code,
                        'designation' => $designation,
                        'reference' => $reference,
                        'prix_achat' => $prix_achat,
                        'prix_vente' => $prix_vente,
                        'seuil' => 10, // Seuil par défaut
                    ]);

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Ligne " . ($imported + 2) . ": " . $e->getMessage() . " | Données: " . implode(' | ', $row);
                }
            }

            fclose($handle);

            // Message de succès avec détails
            $message = "$imported article(s) importé(s) avec succès.";

            if (!empty($errors)) {
                $message .= " " . count($errors) . " erreur(s) détectée(s).";
                // Afficher les erreurs dans la session
                session()->flash('errors_detail', $errors);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Download template for article import
     */
    public function template()
    {
        $headers = ['Désignation', 'Référence', 'Prix Achat', 'Prix Vente', 'Seuil'];
        $filename = 'template_articles.csv';

        return response()->streamDownload(function() use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fclose($handle);
        }, $filename);
    }

    /**
     * Search articles for Select2 AJAX
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');

        $articles = Article::where(function($query) use ($search) {
            $query->where('designation', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
        })
        ->limit(20)
        ->get()
        ->map(function($article) {
            return [
                'id' => $article->id,
                'designation' => $article->designation,
                'code' => $article->code,
                'reference' => $article->reference,
                'prix_vente' => $article->prix_vente
            ];
        });

        return response()->json($articles);
    }
}
