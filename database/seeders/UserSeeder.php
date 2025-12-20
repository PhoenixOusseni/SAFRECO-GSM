<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nom' => 'OUEDRAOGO',
                'prenom' => 'Ousseni',
                'email' => 's-admin@gmail.com',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
