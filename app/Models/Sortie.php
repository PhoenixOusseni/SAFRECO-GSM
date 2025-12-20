<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sortie extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    protected $casts = [
        'date_sortie' => 'date',
        'montant_total' => 'decimal:2',
    ];

    /**
     * Relation avec la table clients
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relation avec les details de sortie
     */
    public function details()
    {
        return $this->hasMany(SortieDetail::class);
    }

    /**
     * Relation avec les depots via les details
     */
    public function depots()
    {
        return $this->hasManyThrough(Depot::class, SortieDetail::class, 'sortie_id', 'id', 'id', 'depot_id');
    }
}
