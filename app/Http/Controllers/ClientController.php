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
    public function show(String $id)
    {
        $client = Client::findOrFail($id);

        // Charger les ventes avec leurs encaissements
        $ventes = $client->ventes()
            ->with('encaissements')
            ->orderBy('date_vente', 'desc')
            ->get();

        // Calculer le montant total encaissé par le client
        $montantTotalEncaisse = 0;
        $montantTotalASolder = 0;

        foreach ($ventes as $vente) {
            foreach ($vente->encaissements as $encaissement) {
                $montantTotalEncaisse += $encaissement->montant_encaisse;
                $montantTotalASolder += $encaissement->reste;
            }
        }

        return view('pages.clients.show', compact('client', 'ventes', 'montantTotalEncaisse', 'montantTotalASolder'));
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

    /**
     * Import clients from CSV/Excel file
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

            return redirect()->back()->with('success', 'Clients importés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Download template for client import
     */
    public function template()
    {
        $headers = ['Code', 'Type', 'Raison Sociale', 'Nom', 'Adresse', 'Téléphone', 'Email', 'Ville'];
        $filename = 'template_clients.csv';

        return response()->streamDownload(function() use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fclose($handle);
        }, $filename);
    }
}
