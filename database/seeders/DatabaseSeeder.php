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
            CountriesTableSeeder::class,
            EmailTemplateTableSeeder::class
        ]);
        $this->call(LessonPricesTableSeeder::class);
        $this->call(CurrencyTableSeeder::class);
        $this->call(EmailTemplateTableSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(EventCategorySeeder::class);
    }
}
