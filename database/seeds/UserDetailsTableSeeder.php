<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('user_details')->truncate();

        DB::table('user_details')->insert(
            [
                [
                    'user_id' => '1',
                    'first_name' => 'admin',
                    'last_name' => ''
                ],[
                    'user_id' => '2',
                    'first_name' => 'customer2',
                    'last_name' => ''
                ],[
                    'user_id' => '3',
                    'first_name' => 'customer3',
                    'last_name' => ''
                ],[
                    'user_id' => '4',
                    'first_name' => 'customer4',
                    'last_name' => ''
                ],
            ]
        );

        Schema::enableForeignKeyConstraints();
    }
}
