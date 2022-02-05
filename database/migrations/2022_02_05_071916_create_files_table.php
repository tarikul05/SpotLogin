<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('object_id', 64)->nullable();
			$table->string('document_id', 64)->nullable();
			$table->integer('visibility')->nullable();
			$table->string('file_type', 50)->nullable();
			$table->string('title', 250)->nullable();
			$table->string('description', 500)->nullable();
			$table->string('path_name', 19)->nullable();
			$table->string('file_name', 50)->nullable();
			$table->string('thumb_name', 50)->nullable();
			$table->string('extension', 10)->nullable();
			$table->string('mime_type', 50)->nullable();
			$table->integer('file_size')->nullable();
			$table->string('orientation', 50)->nullable();
			$table->integer('width')->nullable()->default(0);
			$table->integer('height')->nullable();
			$table->integer('count_pages')->nullable();
			$table->smallInteger('sort_order')->nullable()->default(9999);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('files');
	}

}
