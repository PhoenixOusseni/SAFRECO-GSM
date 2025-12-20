<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_vente',
        'date_vente',
        'client_id',
        'numero_vehicule',
        'chauffeur',
        'montant_total',
        'statut',
    ];

    protected $casts = [
        'date_vente' => 'date',
        'montant_total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement le numéro de vente lors de la création
        static::creating(function ($vente) {
            if (empty($vente->numero_vente)) {
                $vente->numero_vente = self::generateNumeroVente();
            }
        });

        // Supprimer les détails associés lors de la suppression de la vente
        static::deleting(function ($vente) {
            $vente->details()->delete();
        });
    }

    /**
     * Générer un numéro de vente unique
     */
    public static function generateNumeroVente()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "VTE-{$year}{$month}-";

        $lastVente = self::withTrashed()
            ->where('numero_vente', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastVente) {
            $lastNumber = intval(substr($lastVente->numero_vente, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Relation avec le client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relation avec les détails de la vente
     */
    public function details()
    {
        return $this->hasMany(VenteDetail::class);
    }

    /**
     * Relation avec les mouvements de stock
     */
    public function mouvementsStock()
    {
        return $this->morphMany(MouvementStock::class, 'referencable');
    }

    /**
     * Relation avec les encaissements
     */
    public function encaissements()
    {
        return $this->hasMany(Encaissement::class);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeByStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour filtrer par client
     */
    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeByPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
    }

    /**
     * Calculer le montant total à partir des détails
     */
    public function calculerMontantTotal()
    {
        return $this->details()->sum('prix_total');
    }
}
