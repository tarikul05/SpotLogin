<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('event_categories')->delete();
        
        \DB::table('event_categories')->insert(array (
            0 => 
            array (
                'school_id' => 0,
                'title' => 'School invoice',
                'invoiced_type' => 'S',
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'school_id' => 0,
                'title' => 'Teacher invoice',
                'invoiced_type' => 'T',
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            
        ));
    }
}
