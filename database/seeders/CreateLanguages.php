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
       	\DB::table('languages')->insert(['translation_file'=>'en.json','title' => 'English', 'language_code' => 'en', 'abbr_name' => 'en']);
        \DB::table('languages')->insert(['translation_file'=>'fr.json','title' => 'Franch', 'language_code' => 'fr', 'abbr_name' => 'fr']);
        \DB::table('languages')->insert(['translation_file'=>'de.json','title' => 'Deutsch', 'language_code' => 'de', 'abbr_name' => 'de']);
    }
}
