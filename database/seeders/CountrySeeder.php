<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('countries')->updateOrInsert(
            [
                'code' =>'CA'
            ],
            [
                'name' => 'Canada',
                'icon' => '',
                'is_active' => 1,
                'created_at' => now()
            ]
        );
        DB::table('countries')->updateOrInsert(
          [
              'code' =>'FR'
          ],
          [
              'name' => 'France',
              'icon' => '',
              'is_active' => 1,
              'created_at' => now()
          ]
        );
        DB::table('countries')->updateOrInsert(
          [
              'code' =>'CH'
          ],
          [
              'name' => 'Switzerland',
              'icon' => '',
              'is_active' => 1,
              'created_at' => now()
          ]
        );
        DB::table('countries')->updateOrInsert(
          [
              'code' =>'US'
          ],
          [
              'name' => 'United States',
              'icon' => '',
              'is_active' => 1,
              'created_at' => now()
          ]
        );
    }
}
