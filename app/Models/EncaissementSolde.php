<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncaissementSolde extends Model
{
    protected $table = 'encaissement_soldes';

    protected $fillable = [
        'encaissement_id',
        'montant',
        'mode_paiement',
        'date_solde',
        'notes',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_solde' => 'date',
    ];

    public function encaissement()
    {
        return $this->belongsTo(Encaissement::class);
    }
}
