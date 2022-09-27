<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('products')->truncate();

        DB::table('products')->insert(
            [
                [
                    'title' => '10oz (300mL) Hand Sanitizer - Gel',
                    'map' => 2.75,
                    'price' => 3.15,
                    'min_quantity' => 10000
                ],[
                    'title' => '3-Ply ASTM Level 1 Masks',
                    'map' => 0.2,
                    'price' => 0.54,
                    'min_quantity' => 100
                ],[
                    'title' => 'Deluxe Sanitizer Station Package',
                    'map' => 200,
                    'price' => 900,
                    'min_quantity' => 100
                ],
            ]
        );
        Schema::enableForeignKeyConstraints();
    }
}
