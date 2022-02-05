<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTcAcceptedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tc_accepted', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('tc_template_id')->nullable();
			$table->integer('tc_template_lang_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->timestamp('accepted_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tc_accepted');
	}

}
