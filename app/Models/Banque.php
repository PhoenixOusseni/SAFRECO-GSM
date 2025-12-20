<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banque extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    protected $casts = [
        'solde' => 'decimal:2',
    ];

    /**
     * Relation avec les encaissements
     */
    public function encaissements()
    {
        return $this->hasMany(Encaissement::class);
    }

    /**
     * Relation avec les décaissements
     */
    public function decaissements()
    {
        return $this->hasMany(Decaissement::class);
    }
}
