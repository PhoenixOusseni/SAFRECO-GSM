<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'designation',
        'localisation',
        'responsable',
        'contact',
        'description',
    ];

    /**
     * Relation avec les stocks (articles dans ce dépôt)
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Relation avec les articles via stocks
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'stocks')
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
