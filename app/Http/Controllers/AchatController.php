<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\AchatDetail;
use App\Models\Article;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AchatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Achat::with(['fournisseur']);

        // Filtres
        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_achat', [$request->date_debut, $request->date_fin]);
        }

        if ($request->filled('numero_achat')) {
            $query->where('numero_achat', 'like', '%' . $request->numero_achat . '%');
        }

        $achats = $query->orderBy('created_at', 'desc')->paginate(15);
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('pages.achats.index', compact('achats', 'fournisseurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        return view('pages.achats.create', compact('fournisseurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Création de l'achat
        $achat = new Achat();
        $numero_achat = 'ACH-' . str_pad(Achat::max('id') + 1, 5, '0', STR_PAD_LEFT);
        $achat->numero_achat = $numero_achat;
        $achat->designation = $request->designation;
        $achat->date_achat = $request->date_achat;
        $achat->fournisseur_id = $request->fournisseur_id;
        $achat->montant_total = $request->montant_total;
        $achat->save();

        // Redirection avec un message de succès
        return redirect()->route('gestions_achats.index')->with('success', 'Achat ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $achat = Achat::with(['fournisseur'])->findOrFail($id);
        return view('pages.achats.show', compact('achat'));
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit(String $id)
    {
        $achat = Achat::with(['fournisseur'])->findOrFail($id);
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        $articles = Article::orderBy('designation')->get();

        return view('pages.achats.edit', compact('achat', 'fournisseurs', 'articles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $achat = Achat::findOrFail($id);
        $achat->designation = $request->designation;
        $achat->date_achat = $request->date_achat;
        $achat->fournisseur_id = $request->fournisseur_id;
        $achat->montant_total = $request->montant_total;
        $achat->save();

        return redirect()->route('gestions_achats.index')->with('success', 'Achat mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $achat = Achat::findOrFail($id);
        $achat->delete();

        return redirect()->route('gestions_achats.index')->with('success', 'Achat supprimé avec succès.');
    }
}

