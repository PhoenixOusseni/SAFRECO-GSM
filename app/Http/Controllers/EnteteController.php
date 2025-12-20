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
    public function update(Request $request, string $id = null)
    {
        // Si pas d'ID, on met à jour le premier enregistrement (configuration globale)
        if (!$id) {
            $entete = Entete::first() ?? new Entete();
        } else {
            $entete = Entete::findOrFail($id);
        }

        // Mise à jour des champs
        $entete->titre = $request->titre;
        $entete->adresse = $request->adresse;
        $entete->telephone = $request->telephone;
        $entete->email = $request->email;
        $entete->sous_titre = $request->sous_titre;

        // Gestion du logo si une nouvelle image est téléchargée
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo si existant
            if ($entete->logo && \Storage::disk('public')->exists($entete->logo)) {
                \Storage::disk('public')->delete($entete->logo);
            }
            $logo = $request->file('logo')->store('logos', 'public');
            $entete->logo = $logo;
        }

        // Sauvegarde des modifications
        $entete->save();

        // Redirection avec un message de succès
        return redirect()->back()->with('success', 'Informations de la société mises à jour avec succès.');
    }
}

