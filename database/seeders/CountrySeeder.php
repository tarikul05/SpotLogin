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
                'name' => 'Canada'
            ],
            [
                'code' =>'CA',
                'icon' => '',
                'is_active' => 1,
                'created_at' => now()
            ]
        );
        DB::table('countries')->updateOrInsert(
          [
              'name' => 'France'
          ],
          [
              'code' =>'FR',
              'icon' => '',
              'is_active' => 1,
              'created_at' => now()
          ]
        );
        DB::table('countries')->updateOrInsert(
          [
              'name' => 'Switzerland'
          ],
          [
              'code' =>'CH',
              'icon' => '',
              'is_active' => 1,
              'created_at' => now()
          ]
        );
        DB::table('countries')->updateOrInsert(
          [
              'name' => 'United States'
          ],
          [
              'code' =>'US',
              'icon' => '',
              'is_active' => 1,
              'created_at' => now()
          ]
        );
    }
}
