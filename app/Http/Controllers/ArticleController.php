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
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240'
        ]);

        try {
            $file = $request->file('file');
            // Implementation to be added for CSV/Excel parsing
            // Using Laravel Excel or similar library

            return redirect()->back()->with('success', 'Articles importés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Download template for article import
     */
    public function template()
    {
        $headers = ['Code', 'Désignation', 'Référence', 'Prix Achat', 'Prix Vente', 'Seuil'];
        $filename = 'template_articles.csv';

        return response()->streamDownload(function() use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fclose($handle);
        }, $filename);
    }
}
