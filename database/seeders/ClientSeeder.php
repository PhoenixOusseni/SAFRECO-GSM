<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clients')->insert([
            [
                'code' => 'CLT-00001',
                'nom' => 'Client A',
                'adresse' => '123 Rue Principale, Ville A',
                'telephone' => '+1234567890',
                'email' => 'clienta@example.com',
                'ville' => 'OUAGADOUGOU',
                'type' => 'personne',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'code' => 'CLT-00002',
                'nom' => 'Client B',
                'adresse' => '456 Avenue Secondaire, Ville B',
                'telephone' => '+0987654321',
                'email' => 'clientb@example.com',
                'ville' => 'BOBO-DIOULASSO',
                'type' => 'personne',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'code' => 'CLT-00003',
                'nom' => 'Client C',
                'adresse' => '789 Boulevard Tertiaire, Ville C',
                'telephone' => '+1122334455',
                'email' => 'clientc@example.com',
                'ville' => 'KAYA',
                'type' => 'personne',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
