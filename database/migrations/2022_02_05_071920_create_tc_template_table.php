<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTcTemplateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tc_template', function(Blueprint $table)
		{
			$table->integer('id', true);
            $table->integer('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
			$table->char('type', 3)->nullable()->default('A')->comment('A-ALL,S-STUDENT, T-TEACHER, S-SCHOOL ADMIN');
			$table->date('effected_at')->nullable();
			$table->date('effected_till')->nullable();
			$table->char('active_flag', 1)->nullable()->comment('D-RAFT,F-INAL,E-XPIRED');
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
		Schema::drop('tc_template');
	}

}
