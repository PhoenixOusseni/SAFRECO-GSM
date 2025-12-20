<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\EntreeDetail;
use Illuminate\Http\Request;

class DepotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $depots = Depot::all();
        return view('pages.depots.index', compact('depots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.depots.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Création du depot
        $depot = new Depot();
        $code = 'DPT-' . str_pad(Depot::max('id') + 1, 5, '0', STR_PAD_LEFT);
        $depot->code = $code;
        $depot->designation = $request->designation;
        $depot->localisation = $request->localisation;
        $depot->responsable = $request->responsable;
        $depot->contact = $request->contact;
        $depot->save();

        // Redirection avec un message de succès
        return redirect()->route('gestions_depots.index')->with('success', 'Depot ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        // Récupération du depot à afficher
        $depot = Depot::findOrFail($id);
        // Charger les stocks avec les articles et les détails d'entrée
        $depot->load([
            'stocks.article',
            'entreesDetails.entree'
        ]);

        // Récupérer les articles avec leurs derniers mouvements
        $articlesAvecDetails = $depot->stocks()->with(['article', 'depot'])->get()->map(function ($stock) {
            // Chercher la dernière entrée pour cet article dans ce dépôt
            $lastEntree = EntreeDetail::where('article_id', $stock->article_id)
                ->where('depot_id', $stock->depot_id)
                ->with('entree')
                ->latest('created_at')
                ->first();

            return [
                'stock' => $stock,
                'lastEntree' => $lastEntree,
            ];
        });

        return view('pages.depots.show', compact('depot', 'articlesAvecDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        // Récupération du depot à éditer
        $depotFinds = Depot::findOrFail($id);
        return view('pages.depots.edit', compact('depotFinds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // Récupération du depot à mettre à jour
        $depot = Depot::findOrFail($id);
        // Mise à jour des champs
        $depot->designation = $request->designation;
        $depot->localisation = $request->localisation;
        $depot->responsable = $request->responsable;
        $depot->contact = $request->contact;
        // Sauvegarde des modifications
        $depot->save();
        // Redirection avec un message de succès
        return redirect()->route('gestions_depots.index')->with('success', 'Depot mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        // Récupération du depot à supprimer
        $depot = Depot::findOrFail($id);
        // Suppression du depot
        $depot->delete();
        // Redirection avec un message de succès
        return redirect()->route('gestions_depots.index')->with('success', 'Depot supprimé avec succès.');
    }
}
