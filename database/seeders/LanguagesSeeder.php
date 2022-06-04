<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	\DB::table('languages')->insert(['flag_class'=>'flag-icon flag-icon-us','title' => 'English', 'language_code' => 'en', 'abbr_name' => 'en','created_at'=>now(), 'sort_order'=>1]);
        \DB::table('languages')->insert(['flag_class'=>'flag-icon flag-icon-fr','title' => 'French', 'language_code' => 'fr', 'abbr_name' => 'fr','created_at'=>now(), 'sort_order'=>2]);
        \DB::table('languages')->insert(['flag_class'=>'flag-icon flag-icon-de','title' => 'Deutsch', 'language_code' => 'de', 'abbr_name' => 'de','created_at'=>now(), 'sort_order'=>3]);
    }
}
