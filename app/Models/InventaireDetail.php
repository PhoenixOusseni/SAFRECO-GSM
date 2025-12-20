<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventaireDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventaire_id',
        'article_id',
        'quantite_theorique',
        'quantite_physique',
        'ecart_quantite',
        'prix_unitaire',
        'ecart_valeur',
        'observation',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'ecart_valeur' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        // Calculer automatiquement les écarts lors de la création ou mise à jour
        static::saving(function ($detail) {
            $detail->ecart_quantite = $detail->quantite_physique - $detail->quantite_theorique;
            $detail->ecart_valeur = $detail->ecart_quantite * $detail->prix_unitaire;
        });

        // Mettre à jour les totaux de l'inventaire après sauvegarde
        static::saved(function ($detail) {
            $detail->updateInventaireTotals();
        });

        // Mettre à jour les totaux de l'inventaire après suppression
        static::deleted(function ($detail) {
            $detail->updateInventaireTotals();
        });
    }

    /**
     * Mettre à jour les totaux de l'inventaire parent
     */
    public function updateInventaireTotals()
    {
        $inventaire = $this->inventaire;
        if ($inventaire) {
            $inventaire->ecart_total_quantite = $inventaire->details()->sum('ecart_quantite');
            $inventaire->ecart_total_valeur = $inventaire->details()->sum('ecart_valeur');
            $inventaire->saveQuietly();
        }
    }

    /**
     * Relation avec l'inventaire
     */
    public function inventaire()
    {
        return $this->belongsTo(Inventaire::class);
    }

    /**
     * Relation avec l'article
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
