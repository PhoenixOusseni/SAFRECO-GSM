<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VenteDetail extends Model
{
    use HasFactory;

    protected $table = 'ventes_details';

    protected $fillable = [
        'vente_id',
        'article_id',
        'depot_id',
        'quantite',
        'prix_vente',
        'prix_total',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'prix_total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        // Calculer automatiquement le prix total lors de la création ou modification
        static::saving(function ($detail) {
            $detail->prix_total = $detail->quantite * $detail->prix_vente;
        });
    }

    /**
     * Relation avec la vente
     */
    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }

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
     * Vérifier la disponibilité du stock
     */
    public function verifierStock()
    {
        $stock = Stock::where('article_id', $this->article_id)
            ->where('depot_id', $this->depot_id)
            ->first();

        if (!$stock) {
            return [
                'disponible' => false,
                'message' => "L'article n'existe pas dans ce dépôt.",
                'quantite_disponible' => 0,
            ];
        }

        if ($stock->quantite_disponible < $this->quantite) {
            return [
                'disponible' => false,
                'message' => "Stock insuffisant. Disponible: {$stock->quantite_disponible}",
                'quantite_disponible' => $stock->quantite_disponible,
            ];
        }

        return [
            'disponible' => true,
            'message' => 'Stock disponible',
            'quantite_disponible' => $stock->quantite_disponible,
        ];
    }
}
