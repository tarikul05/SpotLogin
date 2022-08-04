<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('languages', function(Blueprint $table)
		{
			$table->string('language_code', 11)->primary();
			$table->string('title', 100)->nullable();
			$table->string('abbr_name', 10)->nullable();
			$table->string('translation_file')->nullable();
			$table->string('flag_class')->nullable();
			$table->integer('sort_order')->nullable();
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
		Schema::drop('languages');
	}

}
