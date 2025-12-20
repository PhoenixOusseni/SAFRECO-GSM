<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entree extends Model
{
    use HasFactory;

    protected $guarded = [
        
    ];

    protected $casts = [
        'date_entree' => 'date',
        'montant_total' => 'decimal:2',
    ];

    /**
     * Relation avec la table fournisseurs
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * Relation avec les details d'entree
     */
    public function details()
    {
        return $this->hasMany(EntreeDetail::class);
    }

    /**
     * Relation avec les depots via les details
     */
    public function depots()
    {
        return $this->hasManyThrough(Depot::class, EntreeDetail::class, 'entree_id', 'id', 'id', 'depot_id');
    }
}
