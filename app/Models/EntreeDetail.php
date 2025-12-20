<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntreeDetail extends Model
{
    use HasFactory;

    protected $table = 'entrees_details';

    protected $fillable = [
        'entree_id',
        'article_id',
        'depot_id',
        'stock',
        'prix_achat',
        'prix_total',
        'observations',
    ];

    protected $casts = [
        'prix_achat' => 'decimal:2',
        'prix_total' => 'decimal:2',
    ];

    /**
     * Relation avec la table entrees
     */
    public function entree()
    {
        return $this->belongsTo(Entree::class);
    }

    /**
     * Relation avec la table articles
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Relation avec la table depots
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }
}
