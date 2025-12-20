<?php

namespace App\Http\Controllers;

use App\Models\Banque;
use Illuminate\Http\Request;

class BanqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banques = Banque::orderBy('designation')->get();
        return view('pages.banques.index', compact('banques'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.banques.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $banque = new Banque();
        $code = 'BNQ-' . str_pad(Banque::max('id') + 1, 5, '0', STR_PAD_LEFT);
        $banque->code = $code;
        $banque->designation = $request->designation;
        $banque->telephone = $request->telephone;
        $banque->email = $request->email;
        $banque->adresse = $request->adresse;
        $banque->numero_compte = $request->numero_compte;
        $banque->solde = $request->solde;
        $banque->save();

        return redirect()->route('gestions_banques.index')
            ->with('success', 'Banque ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $banque = Banque::findOrFail($id);

        // Charger les encaissements et décaissements liés à cette banque
        $encaissements = $banque->encaissements()
            ->with(['vente', 'vente.client'])
            ->orderBy('date_encaissement', 'desc')
            ->get();

        $decaissements = $banque->decaissements()
            ->with(['achat', 'achat.fournisseur'])
            ->orderBy('date_decaissement', 'desc')
            ->get();

        return view('pages.banques.show', compact('banque', 'encaissements', 'decaissements'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $banque = Banque::findOrFail($id);
        return view('pages.banques.edit', compact('banque'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $banque = Banque::findOrFail($id);
        $banque->update([
            'designation' => $request->designation,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'numero_compte' => $request->numero_compte,
            'solde' => $request->solde,]);

        return redirect()->route('gestions_banques.index')
            ->with('success', 'Banque mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $banque = Banque::findOrFail($id);
        $banque->delete();
        return redirect()->route('gestions_banques.index')
            ->with('success', 'Banque supprimée avec succès.');
    }
}
