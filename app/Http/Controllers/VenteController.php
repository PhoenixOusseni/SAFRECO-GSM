<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\VenteDetail;
use App\Models\Client;
use App\Models\Article;
use App\Models\Depot;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vente::with(['client', 'details']);

        // Filtres
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_vente', [$request->date_debut, $request->date_fin]);
        }

        if ($request->filled('numero_vente')) {
            $query->where('numero_vente', 'like', '%' . $request->numero_vente . '%');
        }

        $ventes = $query->orderBy('created_at', 'desc')->paginate(15);
        $clients = Client::orderBy('raison_sociale')->get();

        return view('pages.ventes.index', compact('ventes', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('raison_sociale')->get();
        $articles = Article::orderBy('designation')->get();
        $depots = Depot::orderBy('designation')->get();

        return view('pages.ventes.create', compact('clients', 'articles', 'depots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Vérifier le stock pour chaque article
            foreach ($request->articles as $index => $articleData) {
                $stock = Stock::where('article_id', $articleData['article_id'])
                    ->where('depot_id', $articleData['depot_id'])->first();

                if (!$stock) {
                    $article = Article::find($articleData['article_id']);
                    $depot = Depot::find($articleData['depot_id']);
                    throw new \Exception("L'article {$article->designation} n'existe pas dans le dépôt {$depot->designation}.");
                }

                if ($stock->quantite_disponible < $articleData['quantite']) {
                    $article = Article::find($articleData['article_id']);
                    $depot = Depot::find($articleData['depot_id']);
                    throw new \Exception("Stock insuffisant pour l'article {$article->designation} dans le dépôt {$depot->designation}. Disponible: {$stock->quantite_disponible}");
                }
            }

            // Créer la vente
            $vente = Vente::create([
                'date_vente' => $request->date_vente,
                'client_id' => $request->client_id,
                'numero_vehicule' => $request->numero_vehicule,
                'chauffeur' => $request->chauffeur,
            ]);

            // Créer les détails de la vente et mettre à jour le stock
            $montantTotal = 0;
            foreach ($request->articles as $articleData) {
                $prixTotal = $articleData['quantite'] * $articleData['prix_vente'];
                $montantTotal += $prixTotal;

                VenteDetail::create([
                    'vente_id' => $vente->id,
                    'article_id' => $articleData['article_id'],
                    'depot_id' => $articleData['depot_id'],
                    'quantite' => $articleData['quantite'],
                    'prix_vente' => $articleData['prix_vente'],
                    'prix_total' => $prixTotal,
                ]);

                // Décrémenter le stock dans le dépôt
                $stock = Stock::where('article_id', $articleData['article_id'])
                    ->where('depot_id', $articleData['depot_id'])
                    ->first();
                $stock->decrement('quantite_disponible', $articleData['quantite']);
            }

            // Mettre à jour le montant total
            $vente->update(['montant_total' => $montantTotal]);

            DB::commit();

            return redirect()->route('gestions_ventes.show', $vente->id)
                ->with('success', 'Vente créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Erreur lors de la création de la vente: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $vente = Vente::findOrFail($id);
        $vente->load(['client', 'details.article', 'details.depot']);
        return view('pages.ventes.show', compact('vente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $vente = Vente::findOrFail($id);
        if ($vente->statut !== 'brouillon') {
            return redirect()->route('ventes.show', $vente->id)
                ->with('error', 'Seules les ventes en brouillon peuvent être modifiées.');
        }

        $vente->load(['details.article', 'details.depot']);
        $clients = Client::orderBy('raison_sociale')->get();
        $articles = Article::orderBy('designation')->get();
        $depots = Depot::orderBy('designation')->get();

        return view('pages.ventes.edit', compact('vente', 'clients', 'articles', 'depots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $vente = Vente::findOrFail($id);
        if ($vente->statut !== 'brouillon') {
            return redirect()->route('gestions_ventes.show', $vente->id)
                ->with('error', 'Seules les ventes en brouillon peuvent être modifiées.');
        }

        DB::beginTransaction();
        try {
            // Vérifier le stock pour chaque article
            foreach ($request->articles as $articleData) {
                $stock = Stock::where('article_id', $articleData['article_id'])
                    ->where('depot_id', $articleData['depot_id'])->first();

                if (!$stock) {
                    $article = Article::find($articleData['article_id']);
                    $depot = Depot::find($articleData['depot_id']);
                    throw new \Exception("L'article {$article->designation} n'existe pas dans le dépôt {$depot->designation}.");
                }

                if ($stock->quantite_disponible < $articleData['quantite']) {
                    $article = Article::find($articleData['article_id']);
                    $depot = Depot::find($articleData['depot_id']);
                    throw new \Exception("Stock insuffisant pour l'article {$article->designation} dans le dépôt {$depot->designation}. Disponible: {$stock->quantite_disponible}");
                }
            }

            // Mettre à jour la vente
            $vente->update([
                'date_vente' => $request->date_vente,
                'client_id' => $request->client_id,
                'numero_vehicule' => $request->numero_vehicule,
                'chauffeur' => $request->chauffeur,
                'observation' => $request->observation,
            ]);

            // Supprimer les anciens détails
            $vente->details()->delete();

            // Créer les nouveaux détails
            $montantTotal = 0;
            foreach ($request->articles as $articleData) {
                $prixTotal = $articleData['quantite'] * $articleData['prix_vente'];
                $montantTotal += $prixTotal;

                VenteDetail::create([
                    'vente_id' => $vente->id,
                    'article_id' => $articleData['article_id'],
                    'depot_id' => $articleData['depot_id'],
                    'quantite' => $articleData['quantite'],
                    'prix_vente' => $articleData['prix_vente'],
                    'prix_total' => $prixTotal,
                ]);
            }

            // Mettre à jour le montant total
            $vente->update(['montant_total' => $montantTotal]);

            DB::commit();

            return redirect()->route('ventes.show', $vente->id)
                ->with('success', 'Vente modifiée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification de la vente: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $vente = Vente::findOrFail($id);
        if ($vente->statut !== 'brouillon') {
            return redirect()->route('gestions_ventes.index')
                ->with('error', 'Seules les ventes en brouillon peuvent être supprimées.');
        }

        try {
            $vente->delete();
            return redirect()->route('gestions_ventes.index')
                ->with('success', 'Vente supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('gestions_ventes.index')
                ->with('error', 'Erreur lors de la suppression de la vente: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir le stock disponible pour un article dans un dépôt (AJAX)
     */
    public function getStock(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'depot_id' => 'required|exists:depots,id',
        ]);

        $stock = Stock::where('article_id', $request->article_id)
            ->where('depot_id', $request->depot_id)
            ->first();

        if (!$stock) {
            return response()->json([
                'success' => false,
                'message' => 'Article non disponible dans ce dépôt.',
                'quantite_disponible' => 0,
                'prix_vente' => 0,
            ]);
        }

        $article = Article::find($request->article_id);

        return response()->json([
            'success' => true,
            'quantite_disponible' => $stock->quantite_disponible,
            'prix_vente' => $article->prix_vente,
        ]);
    }

    public function printVente(String $id)
    {
        $vente = Vente::findOrFail($id);
        $vente->load(['client', 'details.article', 'details.depot']);
        return view('pages.ventes.print', compact('vente'));
    }
}
