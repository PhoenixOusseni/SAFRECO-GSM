<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';

    protected $fillable = [
        'article_id',
        'depot_id',
        'type_mouvement',
        'numero_document',
        'quantite',
        'quantite_avant',
        'quantite_apres',
        'prix_unitaire',
        'reference_type',
        'reference_id',
        'observations',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'quantite_avant' => 'decimal:2',
        'quantite_apres' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
    ];

    /**
     * Relation avec Article
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Relation avec Depot
     */
    public function depot(): BelongsTo
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * Relation polymorphique avec la référence (Entree ou Sortie)
     */
    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Scope pour filtrer par type de mouvement
     */
    public function scopeEntrees($query)
    {
        return $query->where('type_mouvement', 'entree');
    }

    /**
     * Scope pour filtrer par type de mouvement
     */
    public function scopeSorties($query)
    {
        return $query->where('type_mouvement', 'sortie');
    }

    /**
     * Scope pour filtrer par article
     */
    public function scopeForArticle($query, $articleId)
    {
        return $query->where('article_id', $articleId);
    }

    /**
     * Scope pour filtrer par dépôt
     */
    public function scopeForDepot($query, $depotId)
    {
        return $query->where('depot_id', $depotId);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeBetweenDates($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('created_at', [$dateDebut, $dateFin]);
    }
}
