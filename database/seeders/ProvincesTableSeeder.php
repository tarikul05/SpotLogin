<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProvincesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('provinces')->delete();
        
        \DB::table('provinces')->insert(array (
            0 => 
            array (
                'id' => 1,
                'country_code' => 'CA',
                'province_code' => 'AB',
                'province_name' => 'Alberta',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'country_code' => 'CA',
                'province_code' => 'BC',
                'province_name' => 'British Columbia',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'country_code' => 'CA',
                'province_code' => 'MB',
                'province_name' => 'Manitoba',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'country_code' => 'CA',
                'province_code' => 'NB',
                'province_name' => 'New Brunswick',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'country_code' => 'CA',
                'province_code' => 'NL',
                'province_name' => 'Newfoundland and Labrador',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'country_code' => 'CA',
                'province_code' => 'NS',
                'province_name' => 'Nova Scotia',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'country_code' => 'CA',
                'province_code' => 'NT',
                'province_name' => 'Northwest Territories',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'country_code' => 'CA',
                'province_code' => 'NU',
                'province_name' => 'Nunavut',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'country_code' => 'CA',
                'province_code' => 'ON',
                'province_name' => 'Ontario',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'country_code' => 'CA',
                'province_code' => 'PE',
                'province_name' => 'Prince Edward Island',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'country_code' => 'CA',
                'province_code' => 'QC',
                'province_name' => 'Quebec',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'country_code' => 'CA',
                'province_code' => 'SK',
                'province_name' => 'Saskatchewan',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'country_code' => 'CA',
                'province_code' => 'YT',
                'province_name' => 'Yukon',
                'is_active' => 1,
                'created_at' => '2022-08-09 21:10:52',
                'modified_at' => '2022-08-09 21:10:52',
                'created_by' => NULL,
                'modified_by' => NULL,
            ),
        ));
        
        
    }
}