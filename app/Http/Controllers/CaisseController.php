<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use Illuminate\Http\Request;

class CaisseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $caisses = Caisse::orderBy('designation')->get();
        return view('pages.caisses.index', compact('caisses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.caisses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $caisse = new Caisse();
        $code = 'CSE-' . str_pad(Caisse::max('id') + 1, 5, '0', STR_PAD_LEFT);
        $caisse->code = $code;
        $caisse->designation = $request->designation;
        $caisse->telephone = $request->telephone;
        $caisse->email = $request->email;
        $caisse->adresse = $request->adresse;
        $caisse->solde = $request->solde;
        $caisse->numero_compte = $request->numero_compte;
        $caisse->save();

        return redirect()->route('gestions_caisses.index')->with('success', 'Caisse ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $caisse = Caisse::findOrFail($id);

        // Charger les encaissements et décaissements liés à cette caisse
        $encaissements = $caisse->encaissements()
            ->with(['vente', 'vente.client'])
            ->orderBy('date_encaissement', 'desc')
            ->get();

        $decaissements = $caisse->decaissements()
            ->with(['achat', 'achat.fournisseur'])
            ->orderBy('date_decaissement', 'desc')
            ->get();

        return view('pages.caisses.show', compact('caisse', 'encaissements', 'decaissements'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $caisse = Caisse::findOrFail($id);
        return view('pages.caisses.edit', compact('caisse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $caisse = Caisse::findOrFail($id);
        $caisse->update([
            'designation' => $request->designation,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'solde' => $request->solde,
            'numero_compte' => $request->numero_compte,
        ]);

        return redirect()->route('gestions_caisses.index')
            ->with('success', 'Caisse mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caisse $caisse)
    {
        $caisse->delete();
        return redirect()->route('gestions_caisses.index')
            ->with('success', 'Caisse supprimée avec succès.');
    }
}
