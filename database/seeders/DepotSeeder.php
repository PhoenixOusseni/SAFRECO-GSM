<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('depots')->insert([
            [
                'code' => 'DPT-00001',
                'designation' => 'Depot Principal',
                'localisation' => '123 Rue du Depot, Ville A',
                'responsable' => 'John Doe',
                'contact' => '+1234567890',
                'stock' =>  0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'DPT-00002',
                'designation' => 'Depot Secondaire',
                'localisation' => '456 Avenue du Stockage, Ville B',
                'responsable' => 'Jane Smith',
                'contact' => '+0987654321',
                'stock' =>  0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'DPT-00003',
                'designation' => 'Depot Tertiaire',
                'localisation' => '789 Boulevard des Entrepots, Ville C',
                'responsable' => 'Alice Johnson',
                'contact' => '+1122334455',
                'stock' =>  0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
