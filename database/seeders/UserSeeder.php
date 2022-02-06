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
            'firstname' =>'SuperAdmin',
            'username' => 'sadmin',
            'email' => 'sadmin@spotlogin.com',
            'person_type' => 'SUPER_ADMIN',
            'password' => Hash::make('12345678'),
            'status' => 1,
            'lastname' => 'admin',
            'is_mail_sent' => 1,
            'is_reset_mail_requested' => 1,
            'user_authorisation' => 1,
            'school_id' => 1,
            'is_active' => 1,
            'created_at' => now()
        ]);
    }
}
