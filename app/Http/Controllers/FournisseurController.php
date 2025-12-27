<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fournisseurs = Fournisseur::all();
        return view('pages.fournisseurs.index', compact('fournisseurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.fournisseurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Création du fournisseur
        $fournisseur = new Fournisseur();
        $code = 'FRS-' . str_pad(Fournisseur::max('id') + 1, 5, '0', STR_PAD_LEFT);
        $fournisseur->code = $code;
        $fournisseur->type = $request->type;
        $fournisseur->nom = $request->nom;
        $fournisseur->adresse = $request->adresse;
        $fournisseur->telephone = $request->telephone;
        $fournisseur->email = $request->email;
        $fournisseur->ville = $request->ville;
        $fournisseur->raison_sociale = $request->raison_sociale;
        $fournisseur->save();

        // Redirection avec un message de succès
        return redirect()->route('gestions_fournisseurs.index')->with('success', 'Fournisseur ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);

        // Charger les achats avec leurs décaissements
        $achats = $fournisseur->achats()->with('decaissements')->orderBy('date_achat', 'desc')->get();

        // Calculer le montant total décaissé au fournisseur
        $montantTotalDecaisse = 0;
        $montantTotalASolder = 0;

        foreach ($achats as $achat) {
            foreach ($achat->decaissements as $decaissement) {
                $montantTotalDecaisse += $decaissement->montant_decaisse;
                $montantTotalASolder += $decaissement->reste;
            }
        }

        return view('pages.fournisseurs.show', compact('fournisseur', 'achats', 'montantTotalDecaisse', 'montantTotalASolder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $fournisseurFinds = Fournisseur::findOrFail($id);
        return view('pages.fournisseurs.edit', compact('fournisseurFinds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->type = $request->type;
        $fournisseur->raison_sociale = $request->raison_sociale;
        $fournisseur->nom = $request->nom;
        $fournisseur->adresse = $request->adresse;
        $fournisseur->telephone = $request->telephone;
        $fournisseur->email = $request->email;
        $fournisseur->ville = $request->ville;
        $fournisseur->save();

        return redirect()->route('gestions_fournisseurs.index')->with('success', 'Fournisseur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->delete();
        return redirect()->route('gestions_fournisseurs.index')->with('success', 'Fournisseur supprimé avec succès.');
    }

    /**
     * Import fournisseurs from CSV/Excel file
     */
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

            // Obtenir le dernier ID pour la génération des codes
            $lastId = Fournisseur::max('id') ?? 0;

            // Parcourir chaque ligne du fichier
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                // Ignorer les lignes complètement vides (toutes les colonnes vides)
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
                    // Mapper les colonnes du CSV
                    // Format attendu: Type, Raison Sociale, Nom, Adresse, Téléphone, Email, Ville
                    $type = trim($row[0] ?? 'entreprise');
                    $raison_sociale = trim($row[1] ?? '');
                    $nom = trim($row[2] ?? '');
                    $adresse = trim($row[3] ?? '');
                    $telephone = trim($row[4] ?? '');
                    $email = trim($row[5] ?? '');
                    $ville = trim($row[6] ?? '');

                    // Debug: Log des données lues (à retirer en production)
                    \Log::info('Import ligne', [
                        'row' => $row,
                        'type' => $type,
                        'raison_sociale' => $raison_sociale,
                        'nom' => $nom,
                        'adresse' => $adresse,
                        'telephone' => $telephone,
                        'email' => $email,
                        'ville' => $ville
                    ]);

                    // Générer un code automatique unique
                    $lastId++;
                    $code = 'FRS-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);

                    // Vérifier si le code existe déjà (sécurité)
                    while (Fournisseur::where('code', $code)->exists()) {
                        $lastId++;
                        $code = 'FRS-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);
                    }

                    // Créer un nouveau fournisseur
                    Fournisseur::create([
                        'code' => $code,
                        'type' => $type,
                        'raison_sociale' => $raison_sociale,
                        'nom' => $nom,
                        'adresse' => $adresse,
                        'telephone' => $telephone,
                        'email' => $email,
                        'ville' => $ville,
                    ]);

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Ligne " . ($imported + 2) . ": " . $e->getMessage() . " | Données: " . implode(' | ', $row);
                }
            }

            fclose($handle);

            // Message de succès avec détails
            $message = "$imported fournisseur(s) importé(s) avec succès.";

            if (!empty($errors)) {
                $message .= " " . count($errors) . " erreur(s) détectée(s).";
                // Afficher les erreurs dans la session
                session()->flash('errors_detail', $errors);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Download template for fournisseur import
     */
    public function template()
    {
        $headers = ['Type', 'Raison Sociale', 'Nom', 'Adresse', 'Téléphone', 'Email', 'Ville'];
        $filename = 'template_fournisseurs.csv';

        return response()->streamDownload(function () use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fclose($handle);
        }, $filename);
    }

    /**
     * Rechercher des fournisseurs pour Select2
     */
    public function search(Request $request)
    {
        $search = $request->input('search');

        $fournisseurs = Fournisseur::where('raison_sociale', 'LIKE', "%{$search}%")
            ->orWhere('nom', 'LIKE', "%{$search}%")
            ->orWhere('code', 'LIKE', "%{$search}%")
            ->orWhere('telephone', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->limit(20)
            ->get(['id', 'raison_sociale', 'nom', 'code']);

        return response()->json($fournisseurs);
    }
}
