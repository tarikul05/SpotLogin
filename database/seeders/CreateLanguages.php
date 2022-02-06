<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CreateLanguages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	\DB::table('languages')->insert(['translation_file'=>'flag-icon flag-icon-us','title' => 'English', 'language_code' => 'en', 'abbr_name' => 'en']);
        \DB::table('languages')->insert(['translation_file'=>'flag-icon flag-icon-fr','title' => 'Franch', 'language_code' => 'fr', 'abbr_name' => 'fr']);
        \DB::table('languages')->insert(['translation_file'=>'flag-icon flag-icon-de','title' => 'Deutsch', 'language_code' => 'de', 'abbr_name' => 'de']);
    }
}
