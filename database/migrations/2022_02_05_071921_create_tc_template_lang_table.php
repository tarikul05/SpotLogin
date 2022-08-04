<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTcTemplateLangTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tc_template_lang', function(Blueprint $table)
		{
			$table->integer('id', true);
            $table->integer('tc_template_id')->nullable();
            $table->foreign('tc_template_id')->references('id')->on('tc_template');
			$table->string('language_id', 10)->default('fr');
			$table->text('tc_text')->nullable();
			$table->text('spp_text')->nullable();
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
		Schema::drop('tc_template_lang');
	}

}
