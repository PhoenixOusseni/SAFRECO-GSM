<?php

namespace App\Http\Controllers;

use App\Models\Inventaire;
use App\Models\InventaireDetail;
use App\Models\Depot;
use App\Models\Article;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventaireController extends Controller
{
    public function index()
    {
        $inventaires = Inventaire::with(['depot', 'user'])->orderBy('date_inventaire', 'desc')->get();
        return view('pages.inventaires.index', compact('inventaires'));
    }

    public function create()
    {
        $depots = Depot::orderBy('designation')->get();
        return view('pages.inventaires.create', compact('depots'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $inventaire = Inventaire::create([
                'date_inventaire' => $request->date_inventaire,
                'depot_id' => $request->depot_id,
                'user_id' => Auth::id(),
                'statut' => 'en_cours',
                'observation' => $request->observation,
            ]);

            if ($request->depot_id) {
                $stocks = Stock::where('depot_id', $request->depot_id)->with('article')->get();
                foreach ($stocks as $stock) {
                    InventaireDetail::create([
                        'inventaire_id' => $inventaire->id,
                        'article_id' => $stock->article_id,
                        'quantite_theorique' => $stock->quantite_disponible,
                        'quantite_physique' => 0,
                        'prix_unitaire' => $stock->article->prix_achat ?? 0,
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('gestions_inventaires.edit', $inventaire->id)->with('success', 'Inventaire créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function show(String $id)
    {
        $inventaire = Inventaire::with(['depot', 'user', 'details.article'])->findOrFail($id);
        return view('pages.inventaires.show', compact('inventaire'));
    }

    public function edit(String $id)
    {
        $inventaire = Inventaire::with(['depot', 'details.article'])->findOrFail($id);
        $depots = Depot::orderBy('designation')->get();
        $articles = Article::orderBy('designation')->get();
        return view('pages.inventaires.edit', compact('inventaire', 'depots', 'articles'));
    }

    public function update(Request $request, String $id)
    {
        try {
            DB::beginTransaction();
            $inventaire = Inventaire::findOrFail($id);

            // Mettre à jour l'inventaire
            $inventaire->update([
                'date_inventaire' => $request->date_inventaire,
                'observation' => $request->observation,
            ]);

            // Mettre à jour les détails
            if ($request->has('details')) {
                foreach ($request->details as $detailId => $detailData) {
                    $detail = InventaireDetail::where('id', $detailId)
                        ->where('inventaire_id', $id)
                        ->first();

                    if ($detail) {
                        $detail->quantite_physique = $detailData['quantite_physique'] ?? 0;
                        $detail->observation = $detailData['observation'] ?? null;
                        $detail->save();
                    }
                }
            }

            DB::commit();
            return redirect()->route('gestions_inventaires.edit', $inventaire->id)->with('success', 'Inventaire mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy(String $id)
    {
        try {
            $inventaire = Inventaire::findOrFail($id);
            if ($inventaire->statut == 'valide') {
                return redirect()->back()->with('error', 'Impossible de supprimer un inventaire validé.');
            }
            $inventaire->delete();
            return redirect()->route('gestions_inventaires.index')->with('success', 'Inventaire supprimé.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function valider(String $id)
    {
        try {
            DB::beginTransaction();
            $inventaire = Inventaire::with('details.article')->findOrFail($id);
            if ($inventaire->statut == 'valide') {
                return redirect()->back()->with('error', 'Inventaire déjà validé.');
            }
            $inventaire->update(['statut' => 'valide']);
            foreach ($inventaire->details as $detail) {
                $stock = Stock::where('article_id', $detail->article_id)->where('depot_id', $inventaire->depot_id)->first();
                if ($stock) {
                    $stock->update(['quantite_disponible' => $detail->quantite_physique]);
                }
            }
            DB::commit();
            return redirect()->route('gestions_inventaires.show', $inventaire->id)->with('success', 'Inventaire validé.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function annuler(String $id)
    {
        try {
            $inventaire = Inventaire::findOrFail($id);
            if ($inventaire->statut == 'valide') {
                return redirect()->back()->with('error', 'Impossible d\'annuler un inventaire validé.');
            }
            $inventaire->update(['statut' => 'annule']);
            return redirect()->route('gestions_inventaires.show', $inventaire->id)->with('success', 'Inventaire annulé.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function print(String $id)
    {
        $inventaire = Inventaire::with(['depot', 'user', 'details.article'])->findOrFail($id);
        return view('pages.inventaires.print', compact('inventaire'));
    }

    public function removeArticle(String $id, String $detailId)
    {
        try {
            $inventaire = Inventaire::findOrFail($id);
            if ($inventaire->statut == 'valide') {
                return redirect()->back()->with('error', 'Impossible de modifier un inventaire validé.');
            }
            $detail = InventaireDetail::where('inventaire_id', $id)->where('id', $detailId)->firstOrFail();
            $detail->delete();
            return redirect()->back()->with('success', 'Article retiré.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
