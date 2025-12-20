<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Decaissement extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_decaissement',
        'montant',
        'montant_decaisse',
        'reste',
        'mode_paiement',
        'reference',
        'achat_id',
        'banque_id',
        'caisse_id',
    ];

    protected $casts = [
        'date_decaissement' => 'date',
        'montant' => 'decimal:2',
        'montant_decaisse' => 'decimal:2',
        'reste' => 'decimal:2',
    ];

    /**
     * Relation avec l'achat
     */
    public function achat()
    {
        return $this->belongsTo(Achat::class);
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
        return $this->hasMany(DecaissementSolde::class);
    }
}

