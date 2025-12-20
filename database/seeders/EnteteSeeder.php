<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnteteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('entetes')->insert([
            [
                'titre' => 'SAFRECO SARL',
                'adresse' => '123 Rue Principale, Ville, Pays',
                'description' => 'Entreprise spécialisée dans la vente de matériel GSM et accessoires.',
                'telephone' => '+1234567890',
                'sous_titre' => 'Votre partenaire de confiance en téléphonie mobile',
                'email' => 'contact@safreco.com',
            ],
        ]);
    }
}
