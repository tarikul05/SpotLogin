<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('school_id', 64);
			$table->integer('visibility_id')->nullable()->default(10);
			$table->integer('event_type')->nullable();
			$table->integer('event_category')->nullable();
			$table->dateTime('date_start')->nullable();
			$table->dateTime('date_end')->nullable();
			$table->integer('duration_minutes')->nullable();
			$table->string('teacher_id', 64)->nullable();
			$table->smallInteger('is_paying')->nullable()->default(1);
			$table->string('event_price', 50)->nullable();
			$table->string('title', 200)->nullable();
			$table->string('description', 500)->nullable();
			$table->string('original_event_id', 64)->nullable();
			$table->smallInteger('is_locked')->nullable()->default(0);
			$table->float('price_amount_sell', 10, 0)->nullable();
			$table->string('price_currency', 3)->nullable();
			$table->float('price_amount_buy', 10, 0)->nullable();
			$table->string('fullday_flag', 1)->nullable()->default('N');
			$table->integer('no_of_students')->nullable()->default(1);
			$table->boolean('event_mode', 1)->nullable()->default(1);
			$table->float('extra_charges', 10, 0)->nullable()->default('0.00');
			$table->integer('location_id')->nullable();
			$table->boolean('is_active')->nullable()->default(1);
			$table->dateTime('created_at')->nullable();
			$table->dateTime('modified_at')->nullable();
			$table->integer('created_by')->nullable();
			$table->integer('modified_by')->nullable();
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
		Schema::drop('events');
	}

}
