<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonPriceTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_price_teachers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('lesson_price_id');
            $table->integer('teacher_id');
            $table->integer('event_category_id')->nullable();
            $table->string('lesson_price_student', 30)->nullable();
            $table->double('price_buy')->nullable();
            $table->double('price_sell')->nullable();
            $table->tinyInteger('is_active')->nullable()->default(1);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_price_teachers');
    }
}
