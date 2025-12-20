<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'date_transfert',
        'article_id',
        'quantite',
        'depot_source_id',
        'depot_destination_id',
        'numero_vehicule',
        'nom_chauffeur',
        'observation',
    ];

    protected $casts = [
        'date_transfert' => 'date',
        'quantite' => 'decimal:2',
    ];

    /**
     * Relation avec l'article transféré
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Relation avec le dépôt source
     */
    public function depotSource()
    {
        return $this->belongsTo(Depot::class, 'depot_source_id');
    }

    /**
     * Relation avec le dépôt destination
     */
    public function depotDestination()
    {
        return $this->belongsTo(Depot::class, 'depot_destination_id');
    }
}
