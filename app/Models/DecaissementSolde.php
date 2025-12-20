<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DecaissementSolde extends Model
{
    use HasFactory;

    protected $fillable = [
        'decaissement_id',
        'date_solde',
        'montant_solde',
        'solde_cumule',
        'mode_paiement',
        'observation',
    ];

    protected $casts = [
        'date_solde' => 'date',
        'montant_solde' => 'decimal:2',
        'solde_cumule' => 'decimal:2',
    ];

    /**
     * Relation avec le décaissement
     */
    public function decaissement()
    {
        return $this->belongsTo(Decaissement::class);
    }
}
