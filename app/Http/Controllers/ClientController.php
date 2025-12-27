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
            $lastId = Client::max('id') ?? 0;

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
                    while (Client::where('code', $code)->exists()) {
                        $lastId++;
                        $code = 'CLT-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);
                    }

                    // Créer un nouveau fournisseur
                    Client::create([
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
            $message = "$imported client(s) importé(s) avec succès.";

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
     * Download template for client import
     */
    public function template()
    {
        $headers = ['Type', 'Raison Sociale', 'Nom', 'Adresse', 'Téléphone', 'Email', 'Ville'];
        $filename = 'template_clients.csv';

        return response()->streamDownload(function() use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fclose($handle);
        }, $filename);
    }

    /**
     * Search clients for Select2 AJAX
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');

        $clients = Client::where(function($query) use ($search) {
            $query->where('raison_sociale', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })
        ->limit(20)
        ->get()
        ->map(function($client) {
            return [
                'id' => $client->id,
                'raison_sociale' => $client->raison_sociale,
                'nom' => $client->nom,
                'code' => $client->code
            ];
        });

        return response()->json($clients);
    }
}
