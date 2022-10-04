<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'school_admin']);
        Role::create(['name' => 'school_employee']);
        Role::create(['name' => 'teachers_admin']);
        Role::create(['name' => 'teachers_all']);
        Role::create(['name' => 'teachers_medium']);
        Role::create(['name' => 'teachers_minimum']);
        Role::create(['name' => 'student']);
        Role::create(['name' => 'parents']);
    }
}
