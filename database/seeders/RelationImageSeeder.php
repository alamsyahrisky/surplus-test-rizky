<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 3; $i++) { 
            DB::table('product_image')->insert([
                'product_id' => $i,
                'image_id' => $i,
            ]);
        }
    }
}
