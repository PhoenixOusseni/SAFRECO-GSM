<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'code',
        'raison_sociale',
        'nom',
        'email',
        'telephone',
        'adresse',
        'ville',
        'type',
    ];

    /**
     * Relation avec les sorties
     */
    public function sorties()
    {
        return $this->hasMany(Sortie::class);
    }
}
