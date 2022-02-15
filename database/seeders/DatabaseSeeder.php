<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            LanguagesSeeder::class,
            CountrySeeder::class,
            EmailTemplateTableSeeder::class
        ]);
        $this->call(EmailTemplateTableSeeder::class);
    }
}
