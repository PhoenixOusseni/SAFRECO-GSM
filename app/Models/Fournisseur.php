<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = [
        'code',
        'raison_sociale',
        'contact',
        'email',
        'telephone',
        'adresse',
        'ville',
    ];

    /**
     * Relation avec les entrées
     */
    public function entrees()
    {
        return $this->hasMany(Entree::class);
    }

    /**
     * Relation avec les achats
     */
    public function achats()
    {
        return $this->hasMany(Achat::class);
    }
}
