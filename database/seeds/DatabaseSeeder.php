<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            [
                'name' => 'Sanjeep Lama',
                'email' => 'sanjeeplama24@gmail.com',
                'password' => Hash::make('sanjeep123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Susmita Rai',
                'email' => 'susmitathulung53@gmail.com',
                'password' => Hash::make('susu123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Prakash Dahal',
                'email' => 'dahalprakash1720@gmail.com',
                'password' => Hash::make('pr@kA$#53'),
                'role' => 'admin',
            ]

        ];
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert($admin);
    }
}
