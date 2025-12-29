<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Article;
use App\Models\Depot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource (tous les stocks)
     */
    public function index(Request $request)
    {
        $query = Stock::with(['article', 'depot']);

        // Filtrer par dépôt si spécifié
        if ($request->has('depot_id') && $request->depot_id) {
            $query->where('depot_id', $request->depot_id);
        }

        // Filtrer par article si spécifié
        if ($request->has('article_id') && $request->article_id) {
            $query->where('article_id', $request->article_id);
        }

        // Filtrer les stocks faibles (quantité < seuil minimum)
        if ($request->has('stock_faible') && $request->stock_faible) {
            $query->whereRaw('quantite_disponible < quantite_minimale');
        }

        // Filtrer les stocks vides
        if ($request->has('stock_vide') && $request->stock_vide) {
            $query->where('quantite_disponible', '<=', 0);
        }

        $stocks = $query->orderBy('quantite_disponible', 'asc')->get();

        // Récupérer les listes pour les filtres
        $depots = Depot::orderBy('designation')->get();
        $articles = Article::orderBy('designation')->get();

        return view('pages.stocks.index', compact('stocks', 'depots', 'articles'));
    }

    /**
     * Afficher les stocks d'un article spécifique dans tous les dépôts
     */
    public function showByArticle($articleId)
    {
        $article = Article::findOrFail($articleId);
        $stocks = Stock::with(['depot'])
            ->where('article_id', $articleId)
            ->orderBy('quantite_disponible', 'desc')
            ->get();

        return view('pages.stocks.by-article', compact('article', 'stocks'));
    }

    /**
     * Afficher les stocks d'un dépôt spécifique
     */
    public function showByDepot($depotId)
    {
        $depot = Depot::findOrFail($depotId);
        $stocks = Stock::with(['article'])
            ->where('depot_id', $depotId)
            ->orderBy('quantite_disponible', 'asc')
            ->get();

        return view('pages.stocks.by-depot', compact('depot', 'stocks'));
    }

    /**
     * Afficher les stocks faibles (en dessous du seuil minimum)
     */
    public function alertes()
    {
        $stocks = Stock::with(['article', 'depot'])
            ->whereRaw('quantite_disponible < quantite_minimale')
            ->orderBy('quantite_disponible', 'asc')
            ->get();

        return view('pages.stocks.alertes', compact('stocks'));
    }

    /**
     * Show the form for editing the specified resource (ajustement manuel du stock)
     */
    public function edit(Stock $stock)
    {
        $stock->load(['article', 'depot']);
        return view('pages.stocks.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage (ajustement manuel)
     */
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantite_disponible' => 'required|numeric|min:0',
            'quantite_reserve' => 'required|numeric|min:0',
            'quantite_minimale' => 'required|numeric|min:0',
            'motif_ajustement' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $ancienneQuantite = $stock->quantite_disponible;

            $stock->update([
                'quantite_disponible' => $validated['quantite_disponible'],
                'quantite_reserve' => $validated['quantite_reserve'],
                'quantite_minimale' => $validated['quantite_minimale'],
            ]);

            DB::commit();

            return redirect()
                ->route('gestions_stocks.index')
                ->with('success', 'Stock ajusté avec succès. ' . "Ancienne quantité: {$ancienneQuantite}, Nouvelle quantité: {$validated['quantite_disponible']}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajustement du stock: ' . $e->getMessage());
        }
    }

    /**
     * Initialiser un stock pour un article dans un dépôt (si inexistant)
     */
    public function create()
    {
        $articles = Article::orderBy('designation')->get();
        $depots = Depot::orderBy('designation')->get();

        return view('pages.stocks.create', compact('articles', 'depots'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'depot_id' => 'required|exists:depots,id',
            'quantite_disponible' => 'required|numeric|min:0',
            'quantite_reserve' => 'nullable|numeric|min:0',
            'quantite_minimale' => 'nullable|numeric|min:0',
        ]);

        // Vérifier si le stock existe déjà
        $existingStock = Stock::where('article_id', $validated['article_id'])->where('depot_id', $validated['depot_id'])->first();

        if ($existingStock) {
            return redirect()->back()->withInput()->with('error', 'Un stock existe déjà pour cet article dans ce dépôt. Utilisez la fonction d\'ajustement.');
        }

        Stock::create([
            'article_id' => $validated['article_id'],
            'depot_id' => $validated['depot_id'],
            'quantite_disponible' => $validated['quantite_disponible'],
            'quantite_reserve' => $validated['quantite_reserve'] ?? 0,
            'quantite_minimale' => $validated['quantite_minimale'] ?? 0,
        ]);

        return redirect()->route('gestions_stocks.index')->with('success', 'Stock initialisé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        $stock->load(['article', 'depot']);

        // Récupérer l'historique des mouvements (entrées et sorties) pour ce stock
        $entrees = DB::table('entrees_details')->join('entrees', 'entrees_details.entree_id', '=', 'entrees.id')->where('entrees_details.article_id', $stock->article_id)->where('entrees_details.depot_id', $stock->depot_id)->where('entrees.statut', 'recu')->select('entrees.date_entree as date', 'entrees.numero_entree as numero', 'entrees_details.quantite', 'entrees_details.prix_unitaire')->orderBy('entrees.date_entree', 'desc')->limit(10)->get();

        $sorties = DB::table('sorties_details')->join('sorties', 'sorties_details.sortie_id', '=', 'sorties.id')->where('sorties_details.article_id', $stock->article_id)->where('sorties_details.depot_id', $stock->depot_id)->where('sorties.statut', 'validee')->select('sorties.date_sortie as date', 'sorties.numero_sortie as numero', 'sorties_details.quantite', 'sorties_details.prix_unitaire')->orderBy('sorties.date_sortie', 'desc')->limit(10)->get();

        return view('pages.stocks.show', compact('stock', 'entrees', 'sorties'));
    }

    /**
     * Remove the specified resource from storage (réinitialiser un stock)
     */
    public function destroy(Stock $stock)
    {
        try {
            $article = $stock->article->designation;
            $depot = $stock->depot->designation;

            $stock->delete();

            return redirect()
                ->route('gestions_stocks.index')
                ->with('success', "Stock de {$article} dans {$depot} supprimé avec succès.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression du stock: ' . $e->getMessage());
        }
    }

    /**
     * Import stocks from CSV/Excel file
     */
    public function import(Request $request)
    {
        
    }

    /**
     * Reset all stocks
     */
    public function reset()
    {
        try {
            Stock::truncate();
            return redirect()->back()->with('success', 'Tous les stocks ont été réinitialisés.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la réinitialisation: ' . $e->getMessage());
        }
    }

    /**
     * Export stocks to CSV
     */
    public function export()
    {
        try {
            $stocks = Stock::with(['article', 'depot'])->get();
            $headers = ['Article Code', 'Article', 'Dépôt', 'Quantité Disponible', 'Quantité Minimale', 'Date Mise à Jour'];

            $filename = 'stocks_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

            return response()->streamDownload(function () use ($stocks, $headers) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, $headers);

                foreach ($stocks as $stock) {
                    fputcsv($handle, [$stock->article->code, $stock->article->designation, $stock->depot->designation, $stock->quantite_disponible, $stock->quantite_minimale, $stock->updated_at->format('d/m/Y H:i')]);
                }

                fclose($handle);
            }, $filename);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }
}
