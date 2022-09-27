<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();

        DB::table('users')->insert(
            [
                [
                    'type' => 'admin',
                    'email' => 'admin@test.com',
                    'password' => bcrypt('admin@test.com')
                ],
                [
                    'type' => 'customer',
                    'email' => 'customer@test.com',
                    'password' => bcrypt('customer@test.com')
                ],
                [
                    'type' => 'customer',
                    'email' => 'customer2@test.com',
                    'password' => bcrypt('customer2@test.com')
                ],
                [
                    'type' => 'customer',
                    'email' => 'customer3@test.com',
                    'password' => bcrypt('customer3@test.com')
                ]
            ]

        );

        Schema::enableForeignKeyConstraints();
    }
}
