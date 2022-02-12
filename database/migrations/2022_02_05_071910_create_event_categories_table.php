<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_categories', function(Blueprint $table)
		{
			$table->integer('id', true);
			// $table->integer('school_id');
            $table->integer('school_id');
            $table->foreign('school_id')->references('id')->on('schools');
			$table->string('invoiced_type', 1)->nullable()->default('S');
			$table->integer('file_id')->nullable();
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
		Schema::drop('event_categories');
	}

}
