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
                'titre' => 'SOCITE 001',
                'adresse' => '123 Rue Principale, Ville, Pays',
                'description' => 'Description de la société 001',
                'telephone' => '+1234567890',
                'sous_titre' => 'Sous-titre de la société 001',
                'email' => 'info@societe001.com',
            ],
        ]);
    }
}
