<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnteteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\EntreeController;
use App\Http\Controllers\SortieController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\RapportStockController;
use App\Http\Controllers\TransfertController;
use App\Http\Controllers\BanqueController;
use App\Http\Controllers\CaisseController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\EncaissementController;
use App\Http\Controllers\AchatController;
use App\Http\Controllers\DecaissementController;



Route::get('/', [PageController::class, 'auth'])->name('login');
Route::post('connexion', [AuthController::class, 'login_admin'])->name('login_admin');
Route::post('deconnexion', [AuthController::class, 'logout'])->name('logout');

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');

    // Gestion des entêtes
    Route::get('entetes/infos_societe', [EnteteController::class, 'index'])->name('entetes.index');
    Route::put('entetes/{id}', [EnteteController::class, 'update'])->name('entetes.update');

    // Gestion des clients
    Route::resource('clients/gestions_clients', ClientController::class);

    // Gestion des articles / produits
    Route::resource('articles/gestions_articles', ArticleController::class);

    // Gestion des fournisseurs
    Route::resource('fournisseurs/gestions_fournisseurs', FournisseurController::class);

    // Gestion des depots
    Route::resource('depots/gestions_depots', DepotController::class);

    // Gestion des entrées (Articles dans depots)
    Route::resource('gestions_entrees', EntreeController::class);
    Route::get('entrees/print/{id}', [EntreeController::class, 'printEntree'])->name('entrees.print');

    // Gestion des sorties (Sorties d'articles des depots)
    Route::resource('gestions_sorties', SortieController::class);
    Route::get('sorties/print/{id}', [SortieController::class, 'printSortie'])->name('sorties.print');

    // Routes additionnelles pour les sorties
    Route::post('sorties/get-stock', [SortieController::class, 'getStock'])->name('sorties.getStock');

    // Routes additionnelles pour les ventes (doivent être avant la route resource)
    Route::post('ventes/get-stock', [VenteController::class, 'getStock'])->name('ventes.getStock');

    // Route des gestion des ventes
    Route::resource('ventes/gestions_ventes', VenteController::class);
    Route::get('ventes/print/{id}', [VenteController::class, 'printVente'])->name('ventes.print');

    // Gestion des achats
    Route::resource('achats/gestions_achats', AchatController::class);


    // Gestion des stocks (Consultation et ajustements)
    Route::resource('stocks/gestions_stocks', StockController::class);

    // Routes additionnelles pour les stocks
    Route::get('stocks/article/{articleId}', [StockController::class, 'showByArticle'])->name('stocks.by-article');
    Route::get('stocks/depot/{depotId}', [StockController::class, 'showByDepot'])->name('stocks.by-depot');
    Route::get('stocks/alertes', [StockController::class, 'alertes'])->name('stocks.alertes');

    // Gestion des transferts entre dépôts
    Route::resource('transferts/gestions_transferts', TransfertController::class);
    Route::get('transferts/print/{id}', [TransfertController::class, 'printTransfert'])->name('transferts.print');

    // Gestion des banques
    Route::resource('banques/gestions_banques', BanqueController::class);

    // Gestion caisses
    Route::resource('caisses/gestions_caisses', CaisseController::class);

    // Gestion des encaissements
    Route::get('encaissements/search-ventes', [EncaissementController::class, 'searchVentes'])->name('encaissements.searchVentes');
    Route::get('encaissements/vente-details/{id}', [EncaissementController::class, 'getVenteDetails'])->name('encaissements.venteDetails');
    Route::get('encaissements/unsettled', [EncaissementController::class, 'unsettled'])->name('gestions_encaissements.unsettled');
    Route::get('encaissements/settle/{id}', [EncaissementController::class, 'settle'])->name('gestions_encaissements.settle');
    Route::post('encaissements/process-settle/{id}', [EncaissementController::class, 'processSettle'])->name('gestions_encaissements.processSettle');
    Route::resource('encaissements/gestions_encaissements', EncaissementController::class);
    Route::get('encaissements/print/{id}', [EncaissementController::class, 'printEncaissement'])->name('encaissements.print');

    // Gestion des decaissements
    Route::get('decaissements/search-achats', [DecaissementController::class, 'searchAchats'])->name('decaissements.searchAchats');
    Route::get('decaissements/achat-details/{id}', [DecaissementController::class, 'getAchatDetails'])->name('decaissements.getAchatDetails');
    Route::get('decaissements/unsettled', [DecaissementController::class, 'unsettled'])->name('gestions_decaissements.unsettled');
    Route::get('decaissements/settle/{id}', [DecaissementController::class, 'settle'])->name('gestions_decaissements.settle');
    Route::post('decaissements/process-settle/{id}', [DecaissementController::class, 'processSettle'])->name('gestions_decaissements.processSettle');
    Route::resource('decaissements/gestions_decaissements', DecaissementController::class);
    Route::get('decaissements/print/{id}', [DecaissementController::class, 'printDecaissement'])->name('decaissements.print');

    // Route API pour récupérer le stock disponible (AJAX)
    Route::get('api/stock-disponible', [TransfertController::class, 'getStockDisponible'])->name('api.stock-disponible');

    // Routes pour les rapports de stock
    Route::prefix('rapports')->group(function () {
        Route::get('dashboard', [RapportStockController::class, 'dashboard'])->name('rapports.dashboard');
        Route::get('etat-stocks', [RapportStockController::class, 'etatStocks'])->name('rapports.etat-stocks');
        Route::get('alertes-stock', [RapportStockController::class, 'alertesStock'])->name('rapports.alertes-stock');
        Route::get('historique-mouvements', [RapportStockController::class, 'historiqueMouvements'])->name('rapports.historique-mouvements');
        Route::get('stocks-par-depot/{depotId?}', [RapportStockController::class, 'stocksParDepot'])->name('rapports.stocks-par-depot');
        Route::get('stocks-par-article/{articleId?}', [RapportStockController::class, 'stocksParArticle'])->name('rapports.stocks-par-article');
        Route::get('valorisation-stock', [RapportStockController::class, 'valorisationStock'])->name('rapports.valorisation-stock');
    });
});
