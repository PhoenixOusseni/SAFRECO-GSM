<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SortieDetail extends Model
{
    use HasFactory;

    protected $table = 'sorties_details';

    protected $fillable = [
        'sortie_id',
        'article_id',
        'depot_id',
        'quantite',
        'prix_vente',
        'prix_total',
        'observations',
    ];

    protected $casts = [
        'prix_vente' => 'decimal:2',
        'prix_total' => 'decimal:2',
    ];

    /**
     * Relation avec la table sorties
     */
    public function sortie()
    {
        return $this->belongsTo(Sortie::class);
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
