<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category_name = [
            'Buah & Sayur',
            'Camilan',
            'Bahan Makanan'
        ];

        for ($i=0; $i < count($category_name); $i++) { 
            DB::table('category')->insert([
                'name' => $category_name[$i],
                'enable' => true,
            ]);
        }
    }
}
