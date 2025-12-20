<?php

namespace App\Http\Controllers;

use App\Models\Decaissement;
use App\Models\DecaissementSolde;
use App\Models\Achat;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DecaissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Decaissement::with(['achat', 'achat.fournisseur']);

        // Filtres
        if ($request->filled('achat_id')) {
            $query->where('achat_id', $request->achat_id);
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_decaissement', [$request->date_debut, $request->date_fin]);
        }

        $decaissements = $query->orderBy('created_at', 'desc')->paginate(15);
        $achats = Achat::with('fournisseur')->orderBy('numero_achat')->get();

        return view('pages.decaissements.index', compact('decaissements', 'achats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $achats = Achat::with('fournisseur')->where('statut', '!=', 'valide')->orderBy('numero_achat')->get();

        return view('pages.decaissements.create', compact('achats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Récupérer l'achat
            $achat = Achat::findOrFail($request->achat_id);

            // Calculer le reste
            $reste = $achat->montant_total - $request->montant_decaisse;

            // Créer le décaissement
            $decaissement = Decaissement::create([
                'date_decaissement' => $request->date_decaissement,
                'achat_id' => $request->achat_id,
                'montant' => $achat->montant_total,
                'montant_decaisse' => $request->montant_decaisse,
                'reste' => $reste,
                'mode_paiement' => $request->mode_paiement,
                'achat_id' => $request->achat_id,
            ]);

            // Changer le statut de l'achat à "valide" avec query builder
            DB::table('achats')->where('id', $request->achat_id)
                ->update(['statut' => 'valide']);

            DB::commit();

            return redirect()
                ->route('gestions_decaissements.show', $decaissement->id)
                ->with('success', 'Décaissement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $decaissement = Decaissement::with(['achat', 'achat.fournisseur', 'soldes'])->findOrFail($id);
        return view('pages.decaissements.show', compact('decaissement'));
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit(String $id)
    {
        $decaissement = Decaissement::with(['achat', 'achat.fournisseur'])->findOrFail($id);
        $achats = Achat::with('fournisseur')->orderBy('numero_achat')->get();

        return view('pages.decaissements.edit', compact('decaissement', 'achats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $decaissement = Decaissement::findOrFail($id);

        try {
            DB::beginTransaction();

            // Récupérer l'achat
            $achat = Achat::findOrFail($request['achat_id']);

            // Calculer le reste
            $reste = $achat->montant_total - $request['montant_decaisse'];

            // Mettre à jour le décaissement
            $decaissement->update([
                'date_decaissement' => $request['date_decaissement'],
                'achat_id' => $request['achat_id'],
                'montant' => $achat->montant_total,
                'montant_decaisse' => $request['montant_decaisse'],
                'reste' => $reste,
                'mode_paiement' => $request['mode_paiement'],
                'achat_id' => $request['achat_id'],
            ]);

            DB::commit();

            return redirect()
                ->route('gestions_decaissements.show', $decaissement->id)
                ->with('success', 'Décaissement modifié avec succès.');
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
        try {
            $decaissement = Decaissement::findOrFail($id);
            $decaissement->delete();

            return redirect()
                ->route('gestions_decaissements.index')
                ->with('success', 'Décaissement supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Rechercher les achats (AJAX)
     */
    public function searchAchats(Request $request)
    {
        $search = $request->get('q', '');

        $achats = Achat::with('fournisseur')
            ->where('statut', '!=', 'valide')
            ->where(function($query) use ($search) {
                $query->where('numero_achat', 'LIKE', "%{$search}%")
                      ->orWhereHas('fournisseur', function($q) use ($search) {
                          $q->where('nom', 'LIKE', "%{$search}%");
                      });
            })
            ->limit(20)->get()->map(function($achat) {
                return [
                    'id' => $achat->id,
                    'text' => $achat->numero_achat . ' - ' . ($achat->fournisseur->nom ?? 'N/A'),
                    'montant_total' => $achat->montant_total,
                    'fournisseur_id' => $achat->fournisseur_id,
                    'fournisseur_nom' => $achat->fournisseur->nom ?? 'N/A',
                ];
            });

        return response()->json(['results' => $achats]);
    }

    /**
     * Récupérer les détails de l'achat (AJAX)
     */
    public function getAchatDetails($id)
    {
        $achat = Achat::with('fournisseur')->find($id);

        if (!$achat) {
            return response()->json(['error' => 'Achat not found'], 404);
        }

        return response()->json([
            'montant_total' => $achat->montant_total ?? 0,
            'fournisseur_id' => $achat->fournisseur_id ?? null,
            'fournisseur_nom' => $achat->fournisseur->nom ?? 'N/A',
        ]);
    }

    /**
     * Afficher les décaissements non soldés
     */
    public function unsettled()
    {
        $decaissements = Decaissement::with(['achat', 'achat.fournisseur'])
            ->where('reste', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.decaissements.unsettled', compact('decaissements'));
    }

    /**
     * Afficher le formulaire de solde
     */
    public function settle(String $id)
    {
        $decaissement = Decaissement::with(['achat', 'achat.fournisseur'])->findOrFail($id);

        if ($decaissement->reste <= 0) {
            return redirect()->route('gestions_decaissements.unsettled')
                ->with('error', 'Ce décaissement est déjà soldé.');
        }

        return view('pages.decaissements.settle', compact('decaissement'));
    }

    /**
     * Traiter le solde du décaissement
     */
    public function processSettle(Request $request, String $id)
    {
        $decaissement = Decaissement::findOrFail($id);

        try {
            DB::beginTransaction();

            // Montant à décaisser (ne doit pas dépasser le reste)
            $montant_solde = min($request->montant_solde, $decaissement->reste);

            // Mettre à jour le décaissement
            $decaissement->montant_decaisse += $montant_solde;
            $decaissement->reste -= $montant_solde;
            $decaissement->mode_paiement = $request->mode_paiement ?? $decaissement->mode_paiement;
            $decaissement->save();

            // Enregistrer le solde dans l'historique
            DecaissementSolde::create([
                'decaissement_id' => $decaissement->id,
                'date_solde' => $request->date_solde ?? date('Y-m-d'),
                'montant_solde' => $montant_solde,
                'solde_cumule' => $decaissement->montant_decaisse,
                'mode_paiement' => $request->mode_paiement,
                'observation' => $request->observation ?? null,
            ]);

            // Si complètement soldé, mettre à jour le statut de l'achat
            if ($decaissement->reste == 0) {
                DB::table('achats')->where('id', $decaissement->achat_id)
                    ->update(['statut' => 'valide']);
            }

            DB::commit();

            return redirect()
                ->route('gestions_decaissements.show', $decaissement->id)
                ->with('success', 'Solde enregistré avec succès. Reste à payer: ' . number_format($decaissement->reste, 0, ',', ' ') . ' FCFA');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Erreur lors du solde: ' . $e->getMessage());
        }
    }

    // print method
    public function printDecaissement($id)
    {
        $decaissement = Decaissement::with(['achat', 'achat.fournisseur'])->findOrFail($id);
        return view('pages.decaissements.print', compact('decaissement'));
    }
}

