<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    protected $casts = [
        'prix_achat' => 'decimal:2',
        'prix_vente' => 'decimal:2',
    ];

    /**
     * Relation avec les stocks (dans tous les dépôts)
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Relation avec les dépôts via stocks
     */
    public function depots()
    {
        return $this->belongsToMany(Depot::class, 'stocks')
                    ->withPivot('quantite_disponible', 'quantite_reserve', 'quantite_minimale')
                    ->withTimestamps();
    }

    /**
     * Relation avec les détails des entrées
     */
    public function entreesDetails()
    {
        return $this->hasMany(EntreeDetail::class);
    }

    /**
     * Relation avec les détails des sorties
     */
    public function sortiesDetails()
    {
        return $this->hasMany(SortieDetail::class);
    }
}
