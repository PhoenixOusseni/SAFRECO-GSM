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

    /**
     * Générer un code-barres unique automatiquement
     * Format: CB + année + numéro séquentiel sur 6 chiffres
     * Exemple: CB2025000001
     */
    public static function generateCodeBarre()
    {
        $year = date('Y');
        $prefix = 'CB' . $year;

        // Récupérer le dernier code-barres de l'année en cours
        $lastArticle = self::where('code_barre', 'LIKE', $prefix . '%')
            ->orderBy('code_barre', 'desc')
            ->first();

        if ($lastArticle) {
            // Extraire le numéro séquentiel du code-barres
            // On retire le préfixe (CB + année = 6 caractères) pour ne garder que le numéro
            $lastCodeBarre = $lastArticle->code_barre;
            $prefixLength = strlen($prefix);
            $lastNumber = intval(substr($lastCodeBarre, $prefixLength));
            $newNumber = $lastNumber + 1;
        } else {
            // Premier code-barres de l'année
            $newNumber = 1;
        }

        // Formater le code-barres: CB + année + numéro sur 6 chiffres
        $codeBarre = $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        // Vérifier l'unicité (sécurité)
        while (self::where('code_barre', $codeBarre)->exists()) {
            $newNumber++;
            $codeBarre = $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
        }

        return $codeBarre;
    }
}
