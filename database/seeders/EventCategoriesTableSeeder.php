<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_categories')->delete();
        
        \DB::table('event_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'school_id' => 0,
                'title' => 'School invoice',
                'invoiced_type' => 'S',
                'file_id' => NULL,
                'is_active' => 1,
                'created_at' => '2022-04-21 19:31:04',
                'modified_at' => '2022-11-11 15:40:39',
                'created_by' => 1,
                'modified_by' => NULL,
                'deleted_at' => NULL,
                'package_invoice' => 0,
                's_std_pay_type' => 0,
                's_thr_pay_type' => 0,
                't_std_pay_type' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'school_id' => 0,
                'title' => 'Teacher invoice',
                'invoiced_type' => 'T',
                'file_id' => NULL,
                'is_active' => 1,
                'created_at' => '2022-04-21 19:31:04',
                'modified_at' => '2022-11-11 15:40:39',
                'created_by' => 1,
                'modified_by' => NULL,
                'deleted_at' => NULL,
                'package_invoice' => 0,
                's_std_pay_type' => 1,
                's_thr_pay_type' => 1,
                't_std_pay_type' => 0,
            )
        ));
        
        
    }
}