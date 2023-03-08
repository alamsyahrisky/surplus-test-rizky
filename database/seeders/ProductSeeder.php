<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product_name = [
            'Es Cendol',
            'Chees Pizza',
            'Roti Maryam'
        ];

        for ($i=0; $i < count($product_name); $i++) { 
            DB::table('product')->insert([
                'name' => $product_name[$i],
                'description' => 'Description '.$product_name[$i],
                'enable' => true,
            ]);
        }
    }
}
