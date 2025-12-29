<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vente;
use App\Models\Achat;
use App\Models\Encaissement;
use App\Models\Decaissement;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Article;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;

class PageController extends Controller
{
    public function auth()
    {
        return view('login-admin');
    }

    public function dashboard()
    {
        // Statistiques générales
        $stats = [
            'total_ventes' => Vente::count(),
            'total_achats' => Achat::count(),
            'total_clients' => Client::count(),
            'total_fournisseurs' => Fournisseur::count(),
            'total_articles' => Article::count(),
            'total_utilisateurs' => User::count(),
        ];

        // Ventes du mois
        $ventes_mois = Vente::where('date_vente', '>=', Carbon::now()->startOfMonth())
            ->sum('montant_total');

        // Achats du mois
        $achats_mois = Achat::where('date_achat', '>=', Carbon::now()->startOfMonth())
            ->sum('montant_total');

        // Encaissements du mois
        $encaissements_mois = Encaissement::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('montant_encaisse');

        // Décaissements du mois
        $decaissements_mois = Decaissement::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('montant_decaisse');

        // Top 5 articles vendus
        $top_articles_ventes = \DB::table('ventes_details')
            ->join('articles', 'ventes_details.article_id', '=', 'articles.id')
            ->select('articles.designation', \DB::raw('SUM(ventes_details.quantite) as total_quantite'),
                     \DB::raw('SUM(ventes_details.prix_total) as total_montant'))
            ->groupBy('articles.id', 'articles.designation')
            ->orderByDesc('total_quantite')
            ->limit(5)
            ->get();

        // Top 5 articles achetés - basé sur le montant des achats
        // Puisque achat_details n'a pas de structure complète, on utilise une approche alternative
        // On récupère juste les achats les plus importants
        $top_articles_achats = Achat::orderByDesc('montant_total')
            ->with(['fournisseur'])
            ->limit(5)
            ->get()
            ->map(function($achat) {
                return (object)[
                    'designation' => $achat->designation ?: 'Achat #' . $achat->numero_achat,
                    'total_quantite' => 1, // Nombre d'achats
                    'total_montant' => $achat->montant_total,
                ];
            });

        // Articles en stock faible
        $stock_faible = Stock::with(['article', 'depot'])
            ->whereRaw('quantite_disponible < quantite_minimale')
            ->orderBy('quantite_disponible')
            ->limit(10)
            ->get();

        // Ventes récentes
        $ventes_recentes = Vente::with(['client'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Achats récents
        $achats_recents = Achat::with(['fournisseur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Ventes aujourd'hui
        $ventes_aujourd_hui = Vente::where('date_vente', Carbon::today())
            ->count();

        // Achats aujourd'hui
        $achats_aujourd_hui = Achat::where('date_achat', Carbon::today())
            ->count();

        // Encaissements aujourd'hui
        $encaissements_aujourd_hui = Encaissement::where('created_at', '>=', Carbon::today())
            ->sum('montant_encaisse');

        // Préparer les statistiques totales
        $total_stats = [
            'ventes' => $stats['total_ventes'],
            'achats' => $stats['total_achats'],
            'clients' => $stats['total_clients'],
            'fournisseurs' => $stats['total_fournisseurs'],
            'articles' => $stats['total_articles'],
            'utilisateurs' => $stats['total_utilisateurs'],
            'encaissements' => Encaissement::count(),
            'decaissements' => Decaissement::count(),
        ];

        // Statistiques d'aujourd'hui
        $today_stats = [
            'ventes_count' => $ventes_aujourd_hui,
            'achats_count' => $achats_aujourd_hui,
            'encaissements' => $encaissements_aujourd_hui,
        ];

        return view('pages.dashboard', compact(
            'total_stats',
            'today_stats',
            'ventes_mois',
            'achats_mois',
            'encaissements_mois',
            'decaissements_mois',
            'top_articles_ventes',
            'top_articles_achats',
            'stock_faible',
            'ventes_recentes',
            'achats_recents'
        ));
    }

    public function etat()
    {
        return view('pages.etats.index');
    }

    public function parametres()
    {
        return view('pages.parametres.index');
    }

    /**
     * Effacer le cache de l'application
     */
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        return redirect()->back()->with('success', 'Le cache a été vidé avec succès.');
    }

    /**
     * Effacer les logs
     */
    public function clearLogs()
    {
        try {
            $logFiles = glob(storage_path('logs/*.log'));
            foreach ($logFiles as $logFile) {
                unlink($logFile);
            }
            return redirect()->back()->with('success', 'Les logs ont été réinitialisés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la réinitialisation des logs.');
        }
    }

    /**
     * Exécuter les migrations
     */
    public function migrate()
    {
        try {
            \Artisan::call('migrate', ['--force' => true]);
            return redirect()->back()->with('success', 'Les migrations ont été exécutées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'exécution des migrations.');
        }
    }

    /**
     * help method
     */
    public function help()
    {
        return view('pages.help');
    }
}


