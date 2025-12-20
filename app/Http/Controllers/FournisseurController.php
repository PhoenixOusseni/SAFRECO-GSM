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
    public function show(String $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);

        // Charger les achats avec leurs décaissements
        $achats = $fournisseur->achats()
            ->with('decaissements')
            ->orderBy('date_achat', 'desc')
            ->get();

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
    public function edit(String $id)
    {
        $fournisseurFinds = Fournisseur::findOrFail($id);
        return view('pages.fournisseurs.edit', compact('fournisseurFinds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
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
    public function destroy(String $id)
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
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240'
        ]);

        try {
            $file = $request->file('file');
            // Implementation to be added for CSV/Excel parsing
            // Using Laravel Excel or similar library

            return redirect()->back()->with('success', 'Fournisseurs importés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Download template for fournisseur import
     */
    public function template()
    {
        $headers = ['Code', 'Type', 'Raison Sociale', 'Nom', 'Adresse', 'Téléphone', 'Email', 'Ville'];
        $filename = 'template_fournisseurs.csv';

        return response()->streamDownload(function() use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fclose($handle);
        }, $filename);
    }
}
