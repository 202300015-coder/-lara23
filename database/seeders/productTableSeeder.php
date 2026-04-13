<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; 

class productTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
 

        Product::create([
            'name' => 'Alvaro',
            'description' => 'Mete goles',
            'descriptionLong' => 'A todos los equipos les mete goles por que es un crack',
            'price' => 9.5
        ]);
    }
}
