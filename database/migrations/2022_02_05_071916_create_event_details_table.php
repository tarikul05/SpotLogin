<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('event_id')->nullable();
			$table->string('teacher_id', 64)->nullable();
			$table->string('student_id', 64)->nullable();
			$table->smallInteger('visibility_id')->nullable()->default(10);
			$table->smallInteger('participation_id')->nullable()->default(10);
			$table->smallInteger('is_locked')->nullable()->default(0);
			$table->smallInteger('price_type_id')->nullable();
			$table->float('buy_price', 10, 0)->nullable();
			$table->float('buy_duration', 10, 0)->nullable();
			$table->float('buy_total', 10, 0)->nullable();
			$table->smallInteger('is_buy_invoiced')->nullable()->default(0);
			$table->string('buy_invoice_id', 64)->nullable();
			$table->float('sell_price', 10, 0)->nullable();
			$table->float('sell_duration', 10, 0)->nullable();
			$table->float('sell_total', 10, 0)->nullable();
			$table->smallInteger('is_sell_invoiced')->nullable()->default(0);
			$table->string('sell_invoice_id', 64)->nullable();
			$table->text('teacher_comment')->nullable();
			$table->text('student_comment')->nullable();
			$table->string('price_currency', 3)->nullable();
			$table->text('private_comment')->nullable();
			$table->float('costs_1', 10, 0)->nullable()->default(0);
			$table->float('costs_2', 10, 0)->nullable()->default(0);
			$table->string('invoice_by_items', 64)->nullable();
			$table->string('source_event_id', 64)->nullable();
			$table->string('billing_method', 3)->nullable()->default('E')->comment('E=Eventwise, M=Monthly & Y=Yearly');
			$table->integer('level_id')->nullable();
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
		Schema::drop('event_details');
	}

}
