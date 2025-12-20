<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entete;

class EnteteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entetes = Entete::all();
        return view('pages.entete.index', compact('entetes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Récupération de l'entête à mettre à jour
        $entete = Entete::findOrFail($id);
        // Mise à jour des champs
        $entete->titre = $request->titre;
        $entete->adresse = $request->adresse;
        $entete->telephone = $request->telephone;
        $entete->email = $request->email;
        $entete->sous_titre = $request->sous_titre;
        $entete->description = $request->description;
        // Gestion du logo si une nouvelle image est téléchargée
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('logos', 'public');
            $entete->logo = $logo;
        }
        // Sauvegarde des modifications
        $entete->save();
        // Redirection avec un message de succès
        return redirect()->back()->with('success', 'Informations de la société mises à jour avec succès.');
    }
}
