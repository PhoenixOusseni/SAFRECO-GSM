<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_inventaire',
        'date_inventaire',
        'depot_id',
        'user_id',
        'statut',
        'observation',
        'ecart_total_valeur',
        'ecart_total_quantite',
    ];

    protected $casts = [
        'date_inventaire' => 'date',
        'ecart_total_valeur' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement le numéro d'inventaire lors de la création
        static::creating(function ($inventaire) {
            if (empty($inventaire->numero_inventaire)) {
                $inventaire->numero_inventaire = self::generateNumeroInventaire();
            }
        });

        // Supprimer les détails associés lors de la suppression de l'inventaire
        static::deleting(function ($inventaire) {
            $inventaire->details()->delete();
        });
    }

    /**
     * Générer un numéro d'inventaire unique
     */
    public static function generateNumeroInventaire()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "INV-{$year}{$month}-";

        $lastInventaire = self::where('numero_inventaire', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInventaire) {
            $lastNumber = intval(substr($lastInventaire->numero_inventaire, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Relation avec le dépôt
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les détails de l'inventaire
     */
    public function details()
    {
        return $this->hasMany(InventaireDetail::class);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeByStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour filtrer par dépôt
     */
    public function scopeByDepot($query, $depotId)
    {
        return $query->where('depot_id', $depotId);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeByPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_inventaire', [$dateDebut, $dateFin]);
    }
}
