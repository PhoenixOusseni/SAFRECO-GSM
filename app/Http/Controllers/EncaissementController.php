<?php

namespace App\Http\Controllers;

use App\Models\Encaissement;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Banque;
use App\Models\Caisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EncaissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $encaissements = Encaissement::with(['vente', 'vente.client'])
            ->orderBy('date_encaissement', 'desc')->paginate(15);

        return view('pages.encaissements.index', compact('encaissements'));
    }

    /**
     * Afficher les encaissements non soldés
     */
    public function unsettled()
    {
        $encaissements = Encaissement::with(['vente', 'vente.client'])
            ->whereRaw('montant_encaisse < montant')
            ->orderBy('date_encaissement', 'asc')->paginate(15);

        return view('pages.encaissements.unsettled', compact('encaissements'));
    }

    /**
     * Afficher le formulaire pour solder un encaissement
     */
    public function settle(String $id)
    {
        $encaissement = Encaissement::with(['vente', 'vente.client'])->findOrFail($id);

        // Vérifier s'il reste quelque chose à payer
        $reste = $encaissement->vente->montant_total - $encaissement->montant_encaisse;
        if ($reste <= 0) {
            return redirect()->route('gestions_encaissements.show', $id)
                ->with('warning', 'Cet encaissement est déjà entièrement payé.');
        }

        return view('pages.encaissements.settle', compact('encaissement', 'reste'));
    }

    /**
     * Traiter le solde d'un encaissement
     */
    public function processSettle(Request $request, String $id)
    {
        $encaissement = Encaissement::findOrFail($id);

        // Validation
        $validated = $request->validate([
            'montant_solde' => 'required|numeric|min:0.01',
            'mode_paiement_solde' => 'required|string|max:255',
            'date_solde' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Calculer le reste actuellement dû
            $reste_actuel = $encaissement->vente->montant_total - $encaissement->montant_encaisse;

            // Vérifier que le montant à solder ne dépasse pas le reste
            if ($validated['montant_solde'] > $reste_actuel) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Le montant à solder ne peut pas dépasser le reste dû (' . number_format($reste_actuel, 2) . ' F CFA).');
            }

            // Calculer le nouveau montant encaissé
            $nouveau_montant_encaisse = $encaissement->montant_encaisse + $validated['montant_solde'];
            $nouveau_reste = $encaissement->vente->montant_total - $nouveau_montant_encaisse;

            // Mettre à jour l'encaissement
            $encaissement->update([
                'montant_encaisse' => $nouveau_montant_encaisse,
                'reste' => $nouveau_reste,
                'date_dernier_solde' => $validated['date_solde'],
                'notes' => ($encaissement->notes ?? '') . "\n[" . now()->format('d/m/Y H:i') . "] Solde de " . number_format($validated['montant_solde'], 2) . " F CFA - Mode: " . $validated['mode_paiement_solde'] . ($validated['notes'] ? ' - ' . $validated['notes'] : ''),
            ]);

            // Mettre à jour le solde de la banque ou de la caisse sélectionnée lors du solde
            // Utiliser la banque/caisse du formulaire de solde si fournie, sinon utiliser celle de l'encaissement d'origine
            $banque_id_solde = $request->input('banque_id') ?? $encaissement->banque_id;
            $caisse_id_solde = $request->input('caisse_id') ?? $encaissement->caisse_id;

            if ($banque_id_solde) {
                $banque = Banque::findOrFail($banque_id_solde);
                $banque->solde = $banque->solde + $validated['montant_solde'];
                $banque->save();
            }

            if ($caisse_id_solde) {
                $caisse = Caisse::findOrFail($caisse_id_solde);
                $caisse->solde = $caisse->solde + $validated['montant_solde'];
                $caisse->save();
            }

            // Enregistrer le solde dans un historique (table helper)
            DB::table('encaissement_soldes')->insert([
                'encaissement_id' => $encaissement->id,
                'montant' => $validated['montant_solde'],
                'mode_paiement' => $validated['mode_paiement_solde'],
                'date_solde' => $validated['date_solde'],
                'notes' => $validated['notes'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('gestions_encaissements.show', $id)
                ->with('success', 'Encaissement soldé avec succès. Montant encaissé: ' . number_format($nouveau_montant_encaisse, 2) . ' F CFA' . ($nouveau_reste > 0 ? ' | Reste: ' . number_format($nouveau_reste, 2) . ' F CFA' : ' | ✓ Entièrement payé'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors du solde: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ventes = Vente::with('client')->get();
        $banques = Banque::all();
        $caisses = Caisse::all();
        return view('pages.encaissements.create', compact('ventes', 'banques', 'caisses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Calculer le reste
            $reste = $request->montant - $request->montant_encaisse;

            // Créer l'encaissement
            $encaissement = Encaissement::create([
                'date_encaissement' => $request->date_encaissement,
                'vente_id' => $request->vente_id,
                'montant' => $request->montant,
                'montant_encaisse' => $request->montant_encaisse,
                'reste' => $reste,
                'mode_paiement' => $request->mode_paiement,
                'banque_id' => $request->banque_id ?? null,
                'caisse_id' => $request->caisse_id ?? null,
                'reference' => $request->reference ?? null,
            ]);

            // Mettre à jour le solde de la banque ou de la caisse
            if ($request->banque_id) {
                $banque = Banque::findOrFail($request->banque_id);
                $banque->solde = $banque->solde + $request->montant_encaisse;
                $banque->save();
            }

            if ($request->caisse_id) {
                $caisse = Caisse::findOrFail($request->caisse_id);
                $caisse->solde = $caisse->solde + $request->montant_encaisse;
                $caisse->save();
            }

            // Changer le statut de la vente à "encaisse" avec query builder
            DB::table('ventes')
                ->where('id', $request->vente_id)
                ->update(['statut' => 'validee']);

            DB::commit();

            return redirect()
                ->route('gestions_encaissements.index')
                ->with('success', 'Encaissement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $encaissement = Encaissement::findOrFail($id);
        $encaissement->load(['vente', 'vente.client']);
        return view('pages.encaissements.show', compact('encaissement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $encaissement = Encaissement::findOrFail($id);
        $encaissement->load(['vente', 'vente.client']);
        return view('pages.encaissements.edit', compact('encaissement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $encaissement = Encaissement::findOrFail($id);

        try {
            DB::beginTransaction();

            // Récupérer l'ancien montant et les anciennes références avant modification
            $ancien_montant_encaisse = $encaissement->montant_encaisse;
            $ancienne_banque_id = $encaissement->banque_id;
            $ancienne_caisse_id = $encaissement->caisse_id;

            // Calculer le reste
            $reste = $request->montant - $request->montant_encaisse;

            $encaissement->update([
                'date_encaissement' => $request->date_encaissement,
                'montant' => $request->montant,
                'montant_encaisse' => $request->montant_encaisse,
                'reste' => $reste,
                'mode_paiement' => $request->mode_paiement,
                'banque_id' => $request->banque_id ?? null,
                'caisse_id' => $request->caisse_id ?? null,
                'reference' => $request->reference ?? null,
            ]);

            // Rétablir le solde de l'ancienne banque ou caisse
            if ($ancienne_banque_id) {
                $ancienne_banque = Banque::find($ancienne_banque_id);
                if ($ancienne_banque) {
                    $ancienne_banque->solde = $ancienne_banque->solde - $ancien_montant_encaisse;
                    $ancienne_banque->save();
                }
            }

            if ($ancienne_caisse_id) {
                $ancienne_caisse = Caisse::find($ancienne_caisse_id);
                if ($ancienne_caisse) {
                    $ancienne_caisse->solde = $ancienne_caisse->solde - $ancien_montant_encaisse;
                    $ancienne_caisse->save();
                }
            }

            // Mettre à jour le solde de la nouvelle banque ou caisse
            if ($request->banque_id) {
                $banque = Banque::findOrFail($request->banque_id);
                $banque->solde = $banque->solde + $request->montant_encaisse;
                $banque->save();
            }

            if ($request->caisse_id) {
                $caisse = Caisse::findOrFail($request->caisse_id);
                $caisse->solde = $caisse->solde + $request->montant_encaisse;
                $caisse->save();
            }

            DB::commit();

            return redirect()
                ->route('gestions_encaissements.index')
                ->with('success', 'Encaissement modifié avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $encaissement = Encaissement::findOrFail($id);
        try {
            DB::beginTransaction();

            // Rétablir le solde de la banque ou de la caisse avant suppression
            if ($encaissement->banque_id) {
                $banque = Banque::find($encaissement->banque_id);
                if ($banque) {
                    $banque->solde = $banque->solde - $encaissement->montant_encaisse;
                    $banque->save();
                }
            }

            if ($encaissement->caisse_id) {
                $caisse = Caisse::find($encaissement->caisse_id);
                if ($caisse) {
                    $caisse->solde = $caisse->solde - $encaissement->montant_encaisse;
                    $caisse->save();
                }
            }

            $encaissement->delete();

            DB::commit();

            return redirect()
                ->route('gestions_encaissements.index')
                ->with('success', 'Encaissement supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Rechercher des ventes pour le select avec recherche
     */
    public function searchVentes(Request $request)
    {
        $search = $request->get('q', '');

        $ventes = Vente::with('client')
            ->where('statut', 'brouillon')
            ->where(function($query) use ($search) {
                $query->where('numero_vente', 'LIKE', "%{$search}%")
                      ->orWhereHas('client', function($q) use ($search) {
                          $q->where('raison_sociale', 'LIKE', "%{$search}%")
                            ->orWhere('nom', 'LIKE', "%{$search}%");
                      });
            })
            ->limit(20)->get()->map(function($vente) {
                return [
                    'id' => $vente->id,
                    'text' => $vente->numero_vente . ' - ' . ($vente->client->raison_sociale ?? $vente->client->nom),
                    'montant_total' => $vente->montant_total,
                    'client_id' => $vente->client_id,
                    'client_nom' => $vente->client->raison_sociale ?? $vente->client->nom,
                ];
            });

        return response()->json(['results' => $ventes]);
    }

    /**
     * Récupérer les détails d'une vente
     */
    public function getVenteDetails($id)
    {
        $vente = Vente::with('client')->findOrFail($id);

        return response()->json([
            'success' => true,
            'vente' => [
                'id' => $vente->id,
                'numero_vente' => $vente->numero_vente,
                'montant_total' => $vente->montant_total,
                'client_id' => $vente->client_id,
                'client_nom' => $vente->client->raison_sociale ?? $vente->client->nom,
            ]
        ]);
    }

    public function printEncaissement(String $id)
    {
        $encaissement = Encaissement::with(['vente', 'vente.client'])->findOrFail($id);
        $entetes = DB::table('entetes')->first();
        return view('pages.encaissements.print', compact('encaissement', 'entetes'));
    }
}
