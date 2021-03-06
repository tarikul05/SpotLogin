<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->updateOrInsert(
            [
                'username' => 'sadmin',
                'email' => 'sadmin@sportlogin.com'
            ],
            [
                'firstname' =>'SuperAdmin',
                'person_type' => 'SUPER_ADMIN',
                'password' => Hash::make('12345678'),
                'firstname' => 'admin',
                'lastname' => 'admin',
                'is_mail_sent' => 1,
                'is_reset_mail_requested' => 1,
                'user_authorisation' => 1,
                'school_id' => 0,
                'is_active' => 1,
                'is_firstlogin'=>0,
                'created_at' => now()
            ]
        );
        $user = User::where('username','sadmin')->first();
        $user->assignRole('superadmin');
    }
}
