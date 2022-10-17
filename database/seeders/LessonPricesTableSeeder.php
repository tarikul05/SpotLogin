<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LessonPricesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('lesson_prices')->delete();
        
        \DB::table('lesson_prices')->insert(array (
            0 => 
            array (
                'id' => 1,
                'lesson_price_student' => 'price_1',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => 1,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'lesson_price_student' => 'price_2',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => 2,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'lesson_price_student' => 'price_3',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => 3,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'lesson_price_student' => 'price_4',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => 4,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'lesson_price_student' => 'price_5',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => 5,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'lesson_price_student' => 'price_6',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => 6,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'lesson_price_student' => 'price_7',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => 7,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'lesson_price_student' => 'price_8',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => 8,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'lesson_price_student' => 'price_9',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => 9,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'lesson_price_student' => 'price_10',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => 10,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'lesson_price_student' => 'price_su',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => -2,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'lesson_price_student' => 'price_fix',
                'event_category' => 0,
                'event_type' => 0,
                'divider' => -1,
                'is_active' => 1,
                'created_at' => NULL,
                'modified_at' => NULL,
                'deleted_at' => NULL,
            ),
            
        ));
        
        
    }
}