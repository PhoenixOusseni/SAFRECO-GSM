<?php

namespace App\Helpers;

use App\Models\Stock;
use App\Models\Article;
use App\Models\Depot;

class StockHelper
{
    /**
     * Obtenir tous les stocks en alerte
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAlertes()
    {
        return Stock::with(['article', 'depot'])
            ->whereRaw('quantite_disponible <= quantite_minimale')
            ->orderBy('quantite_disponible', 'asc')
            ->get();
    }

    /**
     * Vérifier si un article spécifique est en alerte dans un dépôt
     *
     * @param int $articleId
     * @param int $depotId
     * @return bool
     */
    public static function isEnAlerte($articleId, $depotId)
    {
        $stock = Stock::where('article_id', $articleId)->where('depot_id', $depotId)->first();

        if (!$stock) {
            return true; // Pas de stock = alerte critique
        }

        return $stock->quantite_disponible <= $stock->quantite_minimale;
    }

    /**
     * Obtenir le nombre total d'alertes
     *
     * @return int
     */
    public static function countAlertes()
    {
        return Stock::whereRaw('quantite_disponible <= quantite_minimale')->count();
    }

    /**
     * Obtenir les statistiques d'un dépôt
     *
     * @param int $depotId
     * @return array
     */
    public static function getStatsDepot($depotId)
    {
        $stocks = Stock::where('depot_id', $depotId)->get();

        return [
            'total_articles' => $stocks->count(),
            'quantite_totale' => $stocks->sum('quantite_disponible'),
            'articles_en_alerte' => $stocks
                ->filter(function ($stock) {
                    return $stock->quantite_disponible <= $stock->quantite_minimale;
                })
                ->count(),
        ];
    }

    /**
     * Obtenir les statistiques d'un article
     *
     * @param int $articleId
     * @return array
     */
    public static function getStatsArticle($articleId)
    {
        $stocks = Stock::where('article_id', $articleId)->get();

        return [
            'total_depots' => $stocks->count(),
            'quantite_totale' => $stocks->sum('quantite_disponible'),
            'depots_en_alerte' => $stocks
                ->filter(function ($stock) {
                    return $stock->quantite_disponible <= $stock->quantite_minimale;
                })
                ->count(),
        ];
    }

    /**
     * Vérifier si un stock est suffisant pour une quantité demandée
     *
     * @param int $articleId
     * @param int $depotId
     * @param float $quantiteDemandee
     * @return bool
     */
    public static function isStockSuffisant($articleId, $depotId, $quantiteDemandee)
    {
        $stock = Stock::where('article_id', $articleId)->where('depot_id', $depotId)->first();

        if (!$stock) {
            return false;
        }

        return $stock->quantite_disponible >= $quantiteDemandee;
    }

    /**
     * Obtenir la quantité disponible pour un article dans un dépôt
     *
     * @param int $articleId
     * @param int $depotId
     * @return float
     */
    public static function getQuantiteDisponible($articleId, $depotId)
    {
        $stock = Stock::where('article_id', $articleId)->where('depot_id', $depotId)->first();

        return $stock ? $stock->quantite_disponible : 0;
    }

    /**
     * Générer un message d'alerte formaté
     *
     * @param Stock $stock
     * @return string
     */
    public static function generateAlerteMessage($stock)
    {
        $article = $stock->article->designation ?? 'Article inconnu';
        $depot = $stock->depot->designation ?? 'Dépôt inconnu';
        $disponible = $stock->quantite_disponible;
        $minimal = $stock->quantite_minimale;

        if ($disponible == 0) {
            return "ALERTE CRITIQUE : {$article} est en rupture de stock dans {$depot} !";
        }

        if ($disponible < $minimal) {
            return "ALERTE : {$article} a un stock faible dans {$depot} (Disponible: {$disponible}, Minimal: {$minimal})";
        }

        return "ATTENTION : {$article} atteint le seuil minimal dans {$depot} (Disponible: {$disponible}, Minimal: {$minimal})";
    }

    /**
     * Obtenir les articles à commander (stock en alerte)
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getArticlesACommander()
    {
        return Stock::with(['article', 'depot'])
            ->whereRaw('quantite_disponible <= quantite_minimale')->get()
            ->map(function ($stock) {
                return [
                    'article' => $stock->article->designation,
                    'depot' => $stock->depot->designation,
                    'disponible' => $stock->quantite_disponible,
                    'minimal' => $stock->quantite_minimale,
                    'a_commander' => max(0, $stock->quantite_minimale - $stock->quantite_disponible + 10), // +10 de sécurité
                ];
        });
    }
}
