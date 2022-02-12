<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentStudentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parent_students', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('parent_id');
            $table->foreign('parent_id')->references('id')->on('parents');
			$table->integer('student_id');
            $table->foreign('student_id')->references('id')->on('students');
			$table->string('relations', 15)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('parent_students');
	}

}
