<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 3; $i++) { 
            DB::table('category_product')->insert([
                'category_id' => $i,
                'product_id' => $i,
            ]);
        }
    }
}
