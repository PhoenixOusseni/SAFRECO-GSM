<?php

namespace App\Http\Controllers;

use App\Models\Transfert;
use App\Models\Article;
use App\Models\Depot;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransfertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transferts = Transfert::with(['article', 'depotSource', 'depotDestination'])
            ->orderBy('date_transfert', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.transfert.index', compact('transferts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $articles = Article::orderBy('designation')->get();
        $depots = Depot::orderBy('designation')->get();

        return view('pages.transfert.create', compact('articles', 'depots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Générer le code automatiquement
            $lastTransfert = Transfert::latest('id')->first();
            $nextNumber = $lastTransfert ? intval(substr($lastTransfert->code, 4)) + 1 : 1;
            $code = 'TRF-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            // Vérifier le stock disponible dans le dépôt source
            $stockSource = Stock::where('depot_id', $request->depot_source_id)
                ->where('article_id', $request->article_id)->first();

            if (!$stockSource) {
                return back()->withErrors(['article_id' => 'Cet article n\'existe pas dans le dépôt source.'])->withInput();
            }

            $quantiteDisponible = $stockSource->quantite_disponible - $stockSource->quantite_reserve;

            if ($quantiteDisponible < $request->quantite) {
                return back()->withErrors(['quantite' => "Quantité insuffisante dans le dépôt source. Disponible: {$quantiteDisponible}"])->withInput();
            }

            // Créer le transfert avec le code généré
            $transfert = Transfert::create([
                'code' => $code,
                'date_transfert' => $request->date_transfert,
                'article_id' => $request->article_id,
                'quantite' => $request->quantite,
                'depot_source_id' => $request->depot_source_id,
                'depot_destination_id' => $request->depot_destination_id,
                'numero_vehicule' => $request->numero_vehicule,
                'nom_chauffeur' => $request->nom_chauffeur,
                'observation' => $request->observation,
            ]);

            // Diminuer le stock du dépôt source
            $stockSource->quantite_disponible -= $request->quantite;
            $stockSource->save();

            // Augmenter le stock du dépôt destination (ou créer s'il n'existe pas)
            $stockDestination = Stock::where('depot_id', $request->depot_destination_id)
                ->where('article_id', $request->article_id)->first();

            if ($stockDestination) {
                $stockDestination->quantite_disponible += $request->quantite;
                $stockDestination->save();
            } else {
                Stock::create([
                    'depot_id' => $request->depot_destination_id,
                    'article_id' => $request->article_id,
                    'quantite_disponible' => $request->quantite,
                    'quantite_reserve' => 0,
                    'quantite_minimale' => 0,
                ]);
            }

            DB::commit();

            return redirect()->route('transferts.index')->with('success', 'Transfert créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Une erreur est survenue lors du transfert: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $transfert = Transfert::with(['article', 'depotSource', 'depotDestination'])->findOrFail($id);

        return view('pages.transfert.show', compact('transfert'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $transfert = Transfert::findOrFail($id);

        $articles = Article::orderBy('designation')->get();
        $depots = Depot::orderBy('designation')->get();

        return view('pages.transfert.edit', compact('transfert', 'articles', 'depots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $transfert = Transfert::findOrFail($id);
        try {
            DB::beginTransaction();

            // Annuler l'ancien transfert (remettre les stocks à leur état initial)
            $stockSourceAncien = Stock::where('depot_id', $transfert->depot_source_id)
                ->where('article_id', $transfert->article_id)
                ->first();

            if ($stockSourceAncien) {
                $stockSourceAncien->quantite_disponible += $transfert->quantite;
                $stockSourceAncien->save();
            }

            $stockDestinationAncien = Stock::where('depot_id', $transfert->depot_destination_id)
                ->where('article_id', $transfert->article_id)
                ->first();

            if ($stockDestinationAncien) {
                $stockDestinationAncien->quantite_disponible -= $transfert->quantite;
                $stockDestinationAncien->save();
            }

            // Appliquer le nouveau transfert
            $stockSource = Stock::where('depot_id', $request['depot_source_id'])
                ->where('article_id', $request['article_id'])
                ->first();

            if (!$stockSource) {
                DB::rollBack();
                return back()->withErrors(['article_id' => 'Cet article n\'existe pas dans le dépôt source.'])->withInput();
            }

            $quantiteDisponible = $stockSource->quantite_disponible - $stockSource->quantite_reserve;

            if ($quantiteDisponible < $request['quantite']) {
                DB::rollBack();
                return back()->withErrors(['quantite' => "Quantité insuffisante dans le dépôt source. Disponible: {$quantiteDisponible}"])->withInput();
            }

            // Diminuer le stock du dépôt source
            $stockSource->quantite_disponible -= $request['quantite'];
            $stockSource->save();

            // Augmenter le stock du dépôt destination
            $stockDestination = Stock::where('depot_id', $request['depot_destination_id'])
                ->where('article_id', $request['article_id'])
                ->first();

            if ($stockDestination) {
                $stockDestination->quantite_disponible += $request['quantite'];
                $stockDestination->save();
            } else {
                Stock::create([
                    'depot_id' => $request['depot_destination_id'],
                    'article_id' => $request['article_id'],
                    'quantite_disponible' => $request['quantite'],
                    'quantite_reserve' => 0,
                    'quantite_minimale' => 0,
                ]);
            }

            // Mettre à jour le transfert
            $transfert->update($request);

            DB::commit();

            return redirect()->route('transferts.index')->with('success', 'Transfert modifié avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la modification: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transfert $transfert)
    {
        try {
            DB::beginTransaction();

            // Annuler le transfert (remettre les stocks à leur état initial)
            $stockSource = Stock::where('depot_id', $transfert->depot_source_id)
                ->where('article_id', $transfert->article_id)
                ->first();

            if ($stockSource) {
                $stockSource->quantite_disponible += $transfert->quantite;
                $stockSource->save();
            }

            $stockDestination = Stock::where('depot_id', $transfert->depot_destination_id)
                ->where('article_id', $transfert->article_id)
                ->first();

            if ($stockDestination) {
                $stockDestination->quantite_disponible -= $transfert->quantite;
                $stockDestination->save();
            }

            // Supprimer le transfert
            $transfert->delete();

            DB::commit();

            return redirect()->route('transferts.index')->with('success', 'Transfert supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression: ' . $e->getMessage()]);
        }
    }

    /**
     * Get stock disponible pour un article dans un dépôt (AJAX)
     */
    public function getStockDisponible(Request $request)
    {
        $stock = Stock::where('depot_id', $request->depot_id)
            ->where('article_id', $request->article_id)
            ->first();

        if ($stock) {
            $quantiteDisponible = $stock->quantite_disponible - $stock->quantite_reserve;
            return response()->json([
                'success' => true,
                'quantite_disponible' => $quantiteDisponible,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Article non trouvé dans ce dépôt',
        ]);
    }

    // Print transfert function
    public function printTransfert($id)
    {
        $transfert = Transfert::with(['article', 'depotSource', 'depotDestination'])->findOrFail($id);
        return view('pages.transfert.print', compact('transfert'));
    }
}
