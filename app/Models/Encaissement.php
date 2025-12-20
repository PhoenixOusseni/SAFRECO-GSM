<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encaissement extends Model
{
    protected $fillable = [
        'date_encaissement',
        'montant',
        'montant_encaisse',
        'reste',
        'mode_paiement',
        'reference',
        'vente_id',
        'banque_id',
        'caisse_id',
    ];

    protected $casts = [
        'date_encaissement' => 'date',
        'montant' => 'decimal:2',
        'montant_encaisse' => 'decimal:2',
        'reste' => 'decimal:2',
    ];

    /**
     * Relation avec la vente
     */
    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }

    /**
     * Relation avec la banque
     */
    public function banque()
    {
        return $this->belongsTo(Banque::class);
    }

    /**
     * Relation avec la caisse
     */
    public function caisse()
    {
        return $this->belongsTo(Caisse::class);
    }

    /**
     * Relation avec les soldes
     */
    public function soldes()
    {
        return $this->hasMany(EncaissementSolde::class, 'encaissement_id');
    }
}
