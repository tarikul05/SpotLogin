<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' =>'SuperAdmin',
            'username' => 'sadmin',
            'email' => 'sadmin@spotlogin.com',
            'person_type' => 'SUPER_ADMIN',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
    }
}
