<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return view('pages.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Création du client
        $client = new Client();
        $code = 'CLT-' . str_pad(Client::max('id') + 1, 5, '0', STR_PAD_LEFT);
        $client->code = $code;
        $client->type = $request->type;
        $client->raison_sociale = $request->raison_sociale;
        $client->nom = $request->nom;
        $client->adresse = $request->adresse;
        $client->telephone = $request->telephone;
        $client->email = $request->email;
        $client->ville = $request->ville;
        $client->save();

        // Redirection avec un message de succès
        return redirect()->route('gestions_clients.index')->with('success', 'Client ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $clientFinds = Client::findOrFail($id);
        return view('pages.clients.edit', compact('clientFinds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $client = Client::findOrFail($id);
        $client->type = $request->type;
        $client->raison_sociale = $request->raison_sociale;
        $client->nom = $request->nom;
        $client->adresse = $request->adresse;
        $client->telephone = $request->telephone;
        $client->email = $request->email;
        $client->ville = $request->ville;
        $client->save();

        return redirect()->route('gestions_clients.index')->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return redirect()->route('gestions_clients.index')->with('success', 'Client supprimé avec succès.');
    }
}
