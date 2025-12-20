<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achat extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_achat',
        'designation',
        'date_achat',
        'montant_total',
        'fournisseur_id',
        'statut',
    ];

    protected $casts = [
        'date_achat' => 'date',
        'montant_total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement le numéro d'achat lors de la création
        static::creating(function ($achat) {
            if (empty($achat->numero_achat)) {
                $achat->numero_achat = self::generateNumeroAchat();
            }
        });
    }

    /**
     * Générer un numéro d'achat unique
     */
    public static function generateNumeroAchat()
    {
        $year = date('Y');
        $lastAchat = self::whereYear('created_at', $year)->latest('id')->first();
        $number = $lastAchat ? intval(substr($lastAchat->numero_achat, -5)) + 1 : 1;
        return 'ACH-' . $year . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Relation avec le fournisseur
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
}

