<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('cd sp')->delete();

        \DB::table('currencies')->insert(array (
            0 =>
            array (
                'currency_code' => 'AUD',
                'name' => 'AUD',
                'country_code' => NULL,
                'description'=>'Dollar australien',
                'sort_order'=> 6,
                'is_active' => 1,
                'created_at' => now(),
            ),
            1 =>
            array (
              'currency_code' => 'CAD',
              'name' => 'CAD',
              'country_code' => 'CA',
              'description'=>'Dollar canadien',
              'sort_order'=> 7,
              'is_active' => 1,
              'created_at' => now(),
            ),
            2 =>
            array (
              'currency_code' => 'CHF',
              'name' => 'CHF',
              'country_code' => 'CH',
              'description'=>NULL,
              'sort_order'=> 1,
              'is_active' => 1,
              'created_at' => now(),
            ),
            3 =>
            array (
              'currency_code' => 'CNY',
              'name' => 'CNY',
              'country_code' => NULL,
              'description'=>'Yuan ou renminbi chinois',
              'sort_order'=> 10,
              'is_active' => 1,
              'created_at' => now(),
            ),
            4 =>
            array (
              'currency_code' => 'CZK',
              'name' => 'CZK',
              'country_code' => NULL,
              'description'=>'Czech crown',
              'sort_order'=> 15,
              'is_active' => 1,
              'created_at' => now(),
            ),
            5 =>
            array (
              'currency_code' => 'DEM',
              'name' => 'DEM',
              'country_code' => NULL,
              'description'=>NULL,
              'sort_order'=> 2,
              'is_active' => 1,
              'created_at' => now(),
            ),
            6 =>
            array (
              'currency_code' => 'DKK',
              'name' => 'DKK',
              'country_code' => NULL,
              'description'=>'Couronne danoise',
              'sort_order'=> 13,
              'is_active' => 1,
              'created_at' => now(),
            ),
            7 =>
            array (
              'currency_code' => 'EUR',
              'name' => 'EUR',
              'country_code' => 'FR',
              'description'=>'Euro',
              'sort_order'=> 3,
              'is_active' => 1,
              'created_at' => now(),
            ),
            8 =>
            array (
              'currency_code' => 'GBP',
              'name' => 'GBP',
              'country_code' => NULL,
              'description'=>'Livre britannique',
              'sort_order'=> 4,
              'is_active' => 1,
              'created_at' => now(),
            ),
            9 =>
            array (
              'currency_code' => 'JPY',
              'name' => 'JPY',
              'country_code' => NULL,
              'description'=>'Yen japonais',
              'sort_order'=> 9,
              'is_active' => 1,
              'created_at' => now(),
            ),
            10 =>
            array (
              'currency_code' => 'RON',
              'name' => 'RON',
              'country_code' => NULL,
              'description'=>'Leu roumain',
              'sort_order'=> 14,
              'is_active' => 1,
              'created_at' => now(),
            ),
            11 =>
            array (
              'currency_code' => 'RUB',
              'name' => 'RUB',
              'country_code' => NULL,
              'description'=>'Rouble russe',
              'sort_order'=> 12,
              'is_active' => 1,
              'created_at' => now(),
            ),
            12 =>
            array (
              'currency_code' => 'SGD',
              'name' => 'SGD',
              'country_code' => NULL,
              'description'=>'Dollar de Singapour',
              'sort_order'=> 8,
              'is_active' => 1,
              'created_at' => now(),
            ),
            13 =>
            array (
              'currency_code' => 'TRY',
              'name' => 'TRY',
              'country_code' => NULL,
              'description'=>'Livre turque',
              'sort_order'=> 11,
              'is_active' => 1,
              'created_at' => now(),
            ),
            14 =>
            array (
              'currency_code' => 'USD',
              'name' => 'USD',
              'country_code' => 'US',
              'description'=>'Dollar des États-Unis',
              'sort_order'=> 5,
              'is_active' => 1,
              'created_at' => now(),
            ),

        ));


    }
}
