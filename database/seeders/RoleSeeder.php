<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
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
        Schema::disableForeignKeyConstraints();
        \DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'school_admin']);
        Role::create(['name' => 'school_employee']);
        Role::create(['name' => 'teachers_admin']);
        Role::create(['name' => 'teachers_all']);
        Role::create(['name' => 'teachers_medium']);
        Role::create(['name' => 'teachers_minimum']);
        Role::create(['name' => 'student']);
        Role::create(['name' => 'parents']);
        Role::create(['name' => 'read_only']);
        Role::create(['name' => 'single_coach_read_only']);
        Role::create(['name' => 'teacher_read_only']);
    }
}
