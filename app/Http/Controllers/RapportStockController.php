<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Article;
use App\Models\Depot;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RapportStockController extends Controller
{
    /**
     * Afficher l'état global des stocks
     */
    public function etatStocks()
    {
        $stocks = Stock::with(['article', 'depot'])
            ->orderBy('quantite_disponible', 'asc')
            ->get();

        return view('pages.rapports.etat_stocks', compact('stocks'));
    }

    /**
     * Afficher les stocks en alerte (quantité < seuil minimal)
     */
    public function alertesStock()
    {
        $stocks = Stock::with(['article', 'depot'])
            ->whereRaw('quantite_disponible <= quantite_minimale')
            ->orderBy('quantite_disponible', 'asc')
            ->get();

        return view('pages.rapports.alertes_stock', compact('stocks'));
    }

    /**
     * Afficher l'historique des mouvements de stock
     */
    public function historiqueMouvements(Request $request)
    {
        $query = MouvementStock::with(['article', 'depot']);

        // Filtres
        if ($request->filled('article_id')) {
            $query->where('article_id', $request->article_id);
        }

        if ($request->filled('depot_id')) {
            $query->where('depot_id', $request->depot_id);
        }

        if ($request->filled('type_mouvement')) {
            $query->where('type_mouvement', $request->type_mouvement);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $mouvements = $query->orderBy('created_at', 'desc')->paginate(50);
        $articles = Article::orderBy('designation')->get();
        $depots = Depot::orderBy('designation')->get();

        return view('pages.rapports.historique_mouvements', compact('mouvements', 'articles', 'depots'));
    }

    /**
     * Afficher les stocks par dépôt
     */
    public function stocksParDepot($depotId = null)
    {
        if ($depotId) {
            $depot = Depot::findOrFail($depotId);
            $stocks = Stock::with(['article'])
                ->where('depot_id', $depotId)
                ->orderBy('quantite_disponible', 'desc')
                ->get();
        } else {
            $depot = null;
            $stocks = collect();
        }

        $depots = Depot::orderBy('designation')->get();

        return view('pages.rapports.stocks_par_depot', compact('stocks', 'depots', 'depot'));
    }

    /**
     * Afficher les stocks par article
     */
    public function stocksParArticle($articleId = null)
    {
        if ($articleId) {
            $article = Article::findOrFail($articleId);
            $stocks = Stock::with(['depot'])
                ->where('article_id', $articleId)
                ->orderBy('quantite_disponible', 'desc')->get();
        } else {
            $article = null;
            $stocks = collect();
        }

        $articles = Article::orderBy('designation')->get();

        return view('pages.rapports.stocks_par_article', compact('stocks', 'articles', 'article'));
    }

    /**
     * Tableau de bord avec statistiques globales
     */
    public function dashboard()
    {
        // Statistiques générales
        $stats = [
            'total_articles' => Article::count(),
            'total_depots' => Depot::count(),
            'articles_en_alerte' => Stock::whereRaw('quantite_disponible <= quantite_minimale')->count(),
            'valeur_stock_total' => Stock::join('articles', 'stocks.article_id', '=', 'articles.id')
                ->sum(DB::raw('stocks.quantite_disponible * articles.prix_achat')),
        ];

        // Mouvements récents (7 derniers jours)
        $mouvementsRecents = MouvementStock::with(['article', 'depot'])
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top 5 articles en stock
        $topArticles = Stock::with('article')
            ->select('article_id', DB::raw('SUM(quantite_disponible) as total'))
            ->groupBy('article_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Articles en alerte
        $articlesEnAlerte = Stock::with(['article', 'depot'])
            ->whereRaw('quantite_disponible <= quantite_minimale')
            ->orderBy('quantite_disponible', 'asc')
            ->limit(10)
            ->get();

        return view('pages.rapports.dashboard', compact('stats', 'mouvementsRecents', 'topArticles', 'articlesEnAlerte'));
    }

    /**
     * Rapport de valorisation du stock
     */
    public function valorisationStock()
    {
        $stocks = Stock::with(['article', 'depot'])
            ->get()
            ->map(function ($stock) {
                $stock->valeur_totale = $stock->quantite_disponible * $stock->article->prix_achat;
                return $stock;
            })
            ->sortByDesc('valeur_totale');

        $valeurTotale = $stocks->sum('valeur_totale');

        return view('pages.rapports.valorisation_stock', compact('stocks', 'valeurTotale'));
    }
}
