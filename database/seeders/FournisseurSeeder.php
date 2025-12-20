<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fournisseurs')->insert([
            [
                'code' => 'FRS-00001',
                'nom' => 'Fournisseur A',
                'adresse' => '12 Rue du Commerce, Ville A',
                'telephone' => '+221123456789',
                'email' => 'fournisseura@example.com',
                'ville' =>  'DAKAR',
                'type' => 'personne',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FRS-00002',
                'nom' => 'Fournisseur B',
                'adresse' => '34 Avenue des Industries, Ville B',
                'telephone' => '+221987654321',
                'email' => 'fournisseurb@example.com',
                'ville' =>  'DAKAR',
                'type' => 'personne',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FRS-00003',
                'nom' => 'Fournisseur C',
                'adresse' => '56 Boulevard du Marché, Ville C',
                'telephone' => '+221112233445',
                'email' => 'fournisseure@example.com',
                'ville' =>  'DAKAR',
                'type' => 'personne',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FRS-00004',
                'nom' => 'Fournisseur D',
                'adresse' => '78 Impasse des Artisans, Ville D',
                'telephone' => '+221556677889',
                'email' => 'fournisseurd@example.com',
                'ville' =>  'DAKAR',
                'type' => 'personne',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Ajoutez plus de fournisseurs si nécessaire
        ]);
    }
}
