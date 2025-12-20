<?php

namespace App\Http\Controllers;

use App\Models\Sortie;
use App\Models\SortieDetail;
use App\Models\Client;
use App\Models\Article;
use App\Models\Depot;
use App\Models\Stock;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SortieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sorties = Sortie::with(['client', 'details.article', 'details.depot'])
            ->orderBy('date_sortie', 'desc')->get();
        return view('pages.sorties.index', compact('sorties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('raison_sociale')->get();
        $articles = Article::orderBy('designation')->get();
        $depots = Depot::orderBy('designation')->get();

        return view('pages.sorties.create', compact('clients', 'articles', 'depots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Générer le numéro de sortie auto-incrémenté (SRT-00001)
            $lastSortie = Sortie::latest('id')->first();
            $nextNumber = $lastSortie ? intval(substr($lastSortie->numero_sortie, 4)) + 1 : 1;
            $numeroSortie = 'SRT-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            // Créer la sortie principale
            $sortie = Sortie::create([
                'numero_sortie' => $numeroSortie,
                'client_id' => $request->client_id,
                'date_sortie' => $request->date_sortie,
                'numero_facture' => $request->numero_facture,
                'type_sortie' => $request->type_sortie ?? 'vente',
                'observations' => $request->observations,
                'statut' => $request->statut ?? 'validee',
                'montant_total' => 0,
            ]);

            $montantTotal = 0;

            // Traiter les tableaux d'articles
            $articles = $request->articles ?? [];
            $depots = $request->depots ?? [];
            $quantites = $request->quantites ?? [];
            $prix_unitaires = $request->prix_unitaires ?? [];

            foreach ($articles as $index => $articleId) {
                $quantite = $quantites[$index] ?? 0;
                $prixUnitaire = $prix_unitaires[$index] ?? 0;
                $depotId = $depots[$index] ?? null;
                $prixTotal = $quantite * $prixUnitaire;
                $montantTotal += $prixTotal;

                // Créer le détail
                SortieDetail::create([
                    'sortie_id' => $sortie->id,
                    'article_id' => $articleId,
                    'depot_id' => $depotId,
                    'quantite' => $quantite,
                    'prix_vente' => $prixUnitaire,
                    'prix_total' => $prixTotal,
                    'observations' => $request->observations,
                ]);

                // Mettre à jour le stock (quantité négative pour décrémenter)
                if ($request->statut === 'validee' || !$request->statut) {
                    $this->updateStock($articleId, $depotId, -$quantite, $numeroSortie, $prixUnitaire, $sortie->id);
                }
            }

            // Mettre à jour le montant total de la sortie
            $sortie->update(['montant_total' => $montantTotal]);

            DB::commit();

            return redirect()
                ->route('gestions_sorties.index')
                ->with('success', 'Sortie créée avec succès. Numéro: ' . $numeroSortie);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Erreur lors de la création de la sortie: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $sortie = Sortie::with(['client', 'details.article', 'details.depot'])->findOrFail($id);
        return view('pages.sorties.show', compact('sortie'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $sortie = Sortie::findOrFail($id);
        $sortie->load(['details']);
        $clients = Client::orderBy('raison_sociale')->get();
        $articles = Article::orderBy('designation')->get();
        $depots = Depot::orderBy('designation')->get();

        return view('pages.sorties.edit', compact('sortie', 'clients', 'articles', 'depots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sortie = Sortie::findOrFail($id);

        DB::beginTransaction();
        try {
            $oldStatut = $sortie->statut;
            $oldDetails = $sortie->details->toArray();

            // Restaurer le stock des anciens détails si la sortie était validée
            // (on ajoute le stock car c'était une sortie)
            if ($oldStatut === 'validee') {
                foreach ($sortie->details as $oldDetail) {
                    $this->updateStock($oldDetail->article_id, $oldDetail->depot_id, $oldDetail->quantite, $sortie->numero_sortie, $oldDetail->prix_unitaire, $sortie->id);
                }
            }

            // Traiter les tableaux d'articles
            $articles = $request->articles ?? [];
            $depots = $request->depots ?? [];
            $quantites = $request->quantites ?? [];
            $prix_unitaires = $request->prix_vente ?? [];

            // Vérifier qu'il y a au moins un article
            if (empty($articles)) {
                throw new \Exception("Vous devez ajouter au moins un article à la sortie");
            }

            // Vérifier la disponibilité des stocks pour les nouveaux détails si validée
            if ($request->statut === 'validee') {
                foreach ($articles as $index => $articleId) {
                    $depotId = $depots[$index] ?? null;
                    $quantite = $quantites[$index] ?? 0;

                    if (!$articleId || !$depotId || $quantite <= 0) {
                        continue;
                    }

                    $stock = Stock::where('article_id', $articleId)
                        ->where('depot_id', $depotId)
                        ->first();

                    $quantiteDisponible = $stock ? $stock->quantite_disponible : 0;

                    if ($quantiteDisponible < $quantite) {
                        $article = Article::find($articleId);
                        $depot = Depot::find($depotId);
                        throw new \Exception(
                            "Stock insuffisant pour {$article->designation} dans le dépôt {$depot->designation}. " .
                            "Disponible: {$quantiteDisponible}, Demandé: {$quantite}"
                        );
                    }
                }
            }

            // Mettre à jour la sortie principale
            $sortie->update([
                'client_id' => $request->client_id ?? null,
                'date_sortie' => $request->date_sortie,
                'numero_facture' => $request->numero_facture,
                'type_sortie' => $request->type_sortie ?? 'vente',
                'observations' => $request->observations,
                'statut' => $request->statut ?? 'validee',
                'montant_total' => 0,
            ]);

            // Supprimer les anciens détails
            $sortie->details()->delete();

            $montantTotal = 0;

            // Créer les nouveaux détails et mettre à jour les stocks
            foreach ($articles as $index => $articleId) {
                $quantite = (float)($quantites[$index] ?? 0);
                $prixUnitaire = (float)($prix_unitaires[$index] ?? 0);
                $depotId = $depots[$index] ?? null;

                if (!$articleId || !$depotId || $quantite <= 0) {
                    continue;
                }

                $prixTotal = $quantite * $prixUnitaire;
                $montantTotal += $prixTotal;

                SortieDetail::create([
                    'sortie_id' => $sortie->id,
                    'article_id' => $articleId,
                    'depot_id' => $depotId,
                    'quantite' => $quantite,
                    'prix_vente' => $prixUnitaire,
                    'prix_total' => $prixTotal,
                    'observations' => $request->observations,
                ]);

                // Mettre à jour le stock si la sortie est validée (décrémenter)
                if ($request->statut === 'validee') {
                    $this->updateStock($articleId, $depotId, -$quantite, $sortie->numero_sortie, $prixUnitaire, $sortie->id);
                }
            }

            // Mettre à jour le montant total
            $sortie->update(['montant_total' => $montantTotal]);

            DB::commit();

            return redirect()->route('gestions_sorties.index')
                ->with('success', 'Sortie mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour de la sortie: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sortie = Sortie::findOrFail($id);

        DB::beginTransaction();
        try {
            // Restaurer le stock si la sortie était validée
            if ($sortie->statut === 'validee') {
                foreach ($sortie->details as $detail) {
                    $this->updateStock($detail->article_id, $detail->depot_id, $detail->quantite, $sortie->numero_sortie, $detail->prix_unitaire, $sortie->id);
                }
            }

            // Supprimer la sortie (les détails seront supprimés en cascade)
            $sortie->delete();

            DB::commit();

            return redirect()->route('gestions_sorties.index')
                ->with('success', 'Sortie supprimée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la sortie: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour le stock d'un article dans un dépôt
     *
     * @param int $articleId
     * @param int $depotId
     * @param float $quantite (peut être positive pour ajout ou négative pour retrait)
     * @param string|null $numeroDocument
     * @param float|null $prixUnitaire
     * @param int|null $referenceId
     */
    private function updateStock($articleId, $depotId, $quantite, $numeroDocument = null, $prixUnitaire = null, $referenceId = null)
    {
        $stock = Stock::where('article_id', $articleId)
            ->where('depot_id', $depotId)
            ->first();

        $quantiteAvant = $stock ? $stock->quantite_disponible : 0;

        if ($stock) {
            // Le stock existe, on met à jour la quantité disponible
            $stock->increment('quantite_disponible', $quantite);
            $stock->refresh();
        } else {
            // Le stock n'existe pas, on le crée (cas improbable pour une sortie)
            $stock = Stock::create([
                'article_id' => $articleId,
                'depot_id' => $depotId,
                'quantite_disponible' => $quantite,
                'quantite_reserve' => 0,
                'quantite_minimale' => 0,
            ]);
        }

        $quantiteApres = $stock->quantite_disponible;

        // Enregistrer le mouvement dans l'historique
        MouvementStock::create([
            'article_id' => $articleId,
            'depot_id' => $depotId,
            'type_mouvement' => 'sortie',
            'numero_document' => $numeroDocument,
            'quantite' => abs($quantite), // Valeur absolue car quantite est négative pour les sorties
            'quantite_avant' => $quantiteAvant,
            'quantite_apres' => $quantiteApres,
            'prix_unitaire' => $prixUnitaire,
            'reference_type' => 'sortie',
            'reference_id' => $referenceId,
        ]);
    }

    /**
     * Récupérer le stock disponible pour un article dans un dépôt (API helper)
     */
    public function getStock(Request $request)
    {
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'depot_id' => 'required|exists:depots,id',
        ]);

        $stock = Stock::where('article_id', $validated['article_id'])
            ->where('depot_id', $validated['depot_id'])
            ->first();

        return response()->json([
            'quantite_disponible' => $stock ? $stock->quantite_disponible : 0,
            'quantite_reserve' => $stock ? $stock->quantite_reserve : 0,
        ]);
    }

    // Print sortie function
    public function printSortie($id)
    {
        $sortie = Sortie::with(['client', 'details.article', 'details.depot'])->findOrFail($id);
        return view('pages.sorties.print', compact('sortie'));
    }
}
