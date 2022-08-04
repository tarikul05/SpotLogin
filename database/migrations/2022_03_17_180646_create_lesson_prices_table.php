<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_prices', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('lesson_price_student', 30)->nullable();
            $table->smallInteger('event_category')->nullable();
            $table->smallInteger('event_type')->nullable();
            $table->smallInteger('divider')->nullable()->default(1);
            $table->tinyInteger('is_active')->nullable()->default(1);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('modified_at')->nullable();
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
        Schema::dropIfExists('lesson_prices');
    }
}
