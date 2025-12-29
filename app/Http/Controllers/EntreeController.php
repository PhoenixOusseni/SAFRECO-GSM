<?php

namespace App\Http\Controllers;

use App\Models\Entree;
use App\Models\EntreeDetail;
use App\Models\Fournisseur;
use App\Models\Article;
use App\Models\Depot;
use App\Models\Stock;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entrees = Entree::with(['fournisseur', 'details.article', 'details.depot'])
            ->orderBy('date_entree', 'desc')
            ->get();
        return view('pages.entrees.index', compact('entrees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->get();
        $articles = Article::orderBy('designation')->get();
        $depots = Depot::orderBy('designation')->get();

        return view('pages.entrees.create', compact('fournisseurs', 'articles', 'depots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Générer le numéro d'entrée auto-incrémenté (ENT-00001)
            $lastEntree = Entree::latest('id')->first();
            $nextNumber = $lastEntree ? intval(substr($lastEntree->numero_entree, 4)) + 1 : 1;
            $numeroEntree = 'ENT-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            // Créer l'entrée principale
            $entree = Entree::create([
                'numero_entree' => $numeroEntree,
                'fournisseur_id' => $request->fournisseur_id,
                'date_entree' => $request->date_entree,
                'numero_facture' => $request->numero_facture,
                'observations' => $request->observations,
                'statut' => $request->statut ?? 'recu',
                'montant_total' => 0,
            ]);

            $montantTotal = 0;

            // Créer les détails et mettre à jour les stocks
            $articles = $request->articles ?? [];
            $depots = $request->depots ?? [];
            $quantites = $request->stock ?? [];
            $prix_unitaires = $request->prix_achat ?? [];

            foreach ($articles as $index => $articleId) {
                $quantite = $quantites[$index] ?? 0;
                $prixUnitaire = $prix_unitaires[$index] ?? 0;
                $depotId = $depots[$index] ?? null;
                $prixTotal = $quantite * $prixUnitaire;
                $montantTotal += $prixTotal;

                // Créer le détail
                EntreeDetail::create([
                    'entree_id' => $entree->id,
                    'article_id' => $articleId,
                    'depot_id' => $depotId,
                    'stock' => $quantite,
                    'prix_achat' => $prixUnitaire,
                    'prix_total' => $prixTotal,
                    'observations' => $request->observations,
                ]);

                // Mettre à jour le stock si l'entrée est validée (statut = 'recu')
                if ($request->statut === 'recu' || !$request->statut) {
                    $this->updateStock($articleId, $depotId, $quantite, $numeroEntree, $prixUnitaire, $entree->id);
                }
            }

            // Mettre à jour le montant total de l'entrée
            $entree->update(['montant_total' => $montantTotal]);

            DB::commit();

            return redirect()
                ->route('gestions_entrees.index')
                ->with('success', 'Entrée créée avec succès. Numéro: ' . $numeroEntree);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'entrée: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $entree = Entree::findOrFail($id);
        $entree->load(['fournisseur', 'details.article', 'details.depot']);
        return view('pages.entrees.show', compact('entree'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $entree = Entree::findOrFail($id);
        $entree->load(['fournisseur', 'details.article', 'details.depot']);
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->get();
        $articles = Article::orderBy('designation')->get();
        $depots = Depot::orderBy('designation')->get();

        return view('pages.entrees.edit', compact('entree', 'fournisseurs', 'articles', 'depots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $entree = Entree::findOrFail($id);
            $ancienStatut = $entree->statut;

            // Annuler l'impact sur le stock des anciens détails si l'entrée était validée
            if ($ancienStatut === 'recu') {
                foreach ($entree->details as $detail) {
                    $this->updateStock($detail->article_id, $detail->depot_id, -$detail->stock);
                }
            }

            // Supprimer tous les anciens détails
            $entree->details()->delete();

            // Mettre à jour l'entrée principale
            $entree->update([
                'fournisseur_id' => $request->fournisseur_id,
                'date_entree' => $request->date_entree,
                'numero_facture' => $request->numero_facture,
                'observations' => $request->observations,
                'statut' => $request->statut ?? 'recu',
            ]);

            $montantTotal = 0;

            // Créer les nouveaux détails et mettre à jour les stocks
            $articles = $request->articles ?? [];
            $depots = $request->depots ?? [];
            $quantites = $request->stock ?? [];
            $prix_unitaires = $request->prix_achat ?? [];

            foreach ($articles as $index => $articleId) {
                $quantite = $quantites[$index] ?? 0;
                $prixUnitaire = $prix_unitaires[$index] ?? 0;
                $depotId = $depots[$index] ?? null;
                $prixTotal = $quantite * $prixUnitaire;
                $montantTotal += $prixTotal;

                // Créer le nouveau détail
                EntreeDetail::create([
                    'entree_id' => $entree->id,
                    'article_id' => $articleId,
                    'depot_id' => $depotId,
                    'stock' => $quantite,
                    'prix_achat' => $prixUnitaire,
                    'prix_total' => $prixTotal,
                    'observations' => $request->observations,
                ]);

                // Mettre à jour le stock si l'entrée est validée (statut = 'recu')
                if ($request->statut === 'recu' || !$request->statut) {
                    $this->updateStock($articleId, $depotId, $quantite, $entree->numero_entree, $prixUnitaire, $entree->id);
                }
            }

            // Mettre à jour le montant total de l'entrée
            $entree->update(['montant_total' => $montantTotal]);

            DB::commit();

            return redirect()
                ->route('entrees.index')
                ->with('success', 'Entrée modifiée avec succès. Numéro: ' . $entree->numero_entree);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification de l\'entrée: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $entree = Entree::findOrFail($id);

            // Annuler l'impact sur le stock si l'entrée était validée
            if ($entree->statut === 'recu') {
                foreach ($entree->details as $detail) {
                    $this->updateStock($detail->article_id, $detail->depot_id, -$detail->stock);
                }
            }

            // Supprimer les détails puis l'entrée
            $entree->details()->delete();
            $entree->delete();

            DB::commit();

            return redirect()->route('gestions_entrees.index')->with('success', 'Entrée supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression de l\'entrée: ' . $e->getMessage());
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
        $stock = Stock::where('article_id', $articleId)->where('depot_id', $depotId)->first();

        $quantiteAvant = $stock ? $stock->quantite_disponible : 0;

        if ($stock) {
            // Le stock existe déjà, on met à jour la quantité disponible
            $stock->increment('quantite_disponible', $quantite);
            $stock->refresh();
        } else {
            // Le stock n'existe pas encore, on le crée
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
            'type_mouvement' => 'entree',
            'numero_document' => $numeroDocument,
            'quantite' => $quantite,
            'quantite_avant' => $quantiteAvant,
            'quantite_apres' => $quantiteApres,
            'prix_unitaire' => $prixUnitaire,
            'reference_type' => 'entree',
            'reference_id' => $referenceId,
        ]);
    }

    // Print entree function
    public function printEntree(string $id)
    {
        $entree = Entree::findOrFail($id);
        $entree->load(['fournisseur', 'details.article', 'details.depot']);
        return view('pages.entrees.print', compact('entree'));
    }

    // Import des entrées from CSV/Excel file
    public function import(Request $request)
    {
        try {
            // Validation du fichier
            $request->validate([
                'file' => 'required|file|mimes:csv,txt|max:2048'
            ]);

            $file = $request->file('file');

            // Ouvrir le fichier CSV
            $handle = fopen($file->getRealPath(), 'r');

            // Détecter le séparateur (virgule ou point-virgule)
            $firstLine = fgets($handle);
            rewind($handle);

            $delimiter = ',';
            if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                $delimiter = ';';
            }

            // Ignorer la première ligne (en-têtes)
            $header = fgetcsv($handle, 1000, $delimiter);

            $imported = 0;
            $errors = [];

            // Parcourir chaque ligne du fichier
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                // Ignorer les lignes complètement vides
                $hasData = false;
                foreach ($row as $cell) {
                    if (!empty(trim($cell))) {
                        $hasData = true;
                        break;
                    }
                }

                if (!$hasData) {
                    continue;
                }

                try {
                    DB::beginTransaction();

                    // Mapper les colonnes du CSV
                    // Format: Code Fournisseur, Date Entrée, N° Facture, Observations, Statut, Code Article, Code Dépôt, Quantité, Prix Achat
                    $codeFournisseur = trim($row[0] ?? '');
                    $dateEntree = trim($row[1] ?? date('Y-m-d'));
                    $numeroFacture = trim($row[2] ?? '');
                    $observations = trim($row[3] ?? '');
                    $statut = trim($row[4] ?? 'recu');
                    $codeArticle = trim($row[5] ?? '');
                    $codeDepot = trim($row[6] ?? '');
                    $quantite = floatval(trim($row[7] ?? 0));
                    $prixAchat = floatval(trim($row[8] ?? 0));

                    // Valider le statut
                    if (!in_array($statut, ['recu', 'en_attente', 'rejete'])) {
                        $statut = 'recu';
                    }

                    // Trouver le fournisseur par code
                    $fournisseur = Fournisseur::where('code', $codeFournisseur)->first();
                    if (!$fournisseur) {
                        throw new \Exception("Fournisseur avec le code '{$codeFournisseur}' non trouvé");
                    }

                    // Trouver l'article par code
                    $article = Article::where('code', $codeArticle)->first();
                    if (!$article) {
                        throw new \Exception("Article avec le code '{$codeArticle}' non trouvé");
                    }

                    // Trouver le dépôt par code
                    $depot = Depot::where('code', $codeDepot)->first();
                    if (!$depot) {
                        throw new \Exception("Dépôt avec le code '{$codeDepot}' non trouvé");
                    }

                    // Générer le numéro d'entrée unique
                    $lastEntree = Entree::latest('id')->first();
                    $nextNumber = $lastEntree ? intval(substr($lastEntree->numero_entree, 4)) + 1 : 1;
                    $numeroEntree = 'ENT-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

                    // Créer l'entrée principale
                    $entree = Entree::create([
                        'numero_entree' => $numeroEntree,
                        'fournisseur_id' => $fournisseur->id,
                        'date_entree' => $dateEntree,
                        'numero_facture' => $numeroFacture,
                        'observations' => $observations,
                        'statut' => $statut,
                        'montant_total' => $quantite * $prixAchat,
                    ]);

                    // Créer le détail de l'entrée
                    EntreeDetail::create([
                        'entree_id' => $entree->id,
                        'article_id' => $article->id,
                        'depot_id' => $depot->id,
                        'stock' => $quantite,
                        'prix_achat' => $prixAchat,
                        'prix_total' => $quantite * $prixAchat,
                        'observations' => $observations,
                    ]);

                    // Mettre à jour le stock si l'entrée est validée
                    if ($statut === 'recu') {
                        $this->updateStock($article->id, $depot->id, $quantite, $numeroEntree, $prixAchat, $entree->id);
                    }

                    DB::commit();
                    $imported++;

                } catch (\Exception $e) {
                    DB::rollBack();
                    $errors[] = "Ligne " . ($imported + 2) . ": " . $e->getMessage() . " | Données: " . implode(' | ', $row);
                }
            }

            fclose($handle);

            // Message de succès avec détails
            $message = "$imported entrée(s) importée(s) avec succès.";

            if (!empty($errors)) {
                $message .= " " . count($errors) . " erreur(s) détectée(s).";
                session()->flash('errors_detail', $errors);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    // Export template des entrées to CSV file
    public function template()
    {
        $headers = ['Code Fournisseur', 'Date Entrée (YYYY-MM-DD)', 'Numéro Facture', 'Observations', 'Statut (recu/en_attente/rejete)', 'Code Article', 'Code Dépôt', 'Quantité', 'Prix Achat'];
        $filename = 'template_entrees.csv';

        return response()->streamDownload(function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers, ';');
            fclose($file);
        }, $filename);
    }
}
