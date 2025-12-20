<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'depot_id',
        'quantite_disponible',
        'quantite_reserve',
        'quantite_minimale',
    ];

    /**
     * Relation avec l'article
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Relation avec le dépôt
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * Getter - Quantité réelle disponible (disponible - réservée)
     */
    public function getQuantiteReelleAttribute()
    {
        return $this->attributes['quantite_disponible'] - $this->attributes['quantite_reserve'];
    }
}
