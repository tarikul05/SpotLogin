<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_items', function(Blueprint $table)
		{
			$table->integer('id', true);
			// $table->string('school_id', 64);
            $table->integer('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
			// $table->string('invoice_id', 64);
            $table->integer('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices');
			$table->smallInteger('is_locked')->nullable()->default(0);
			$table->string('caption', 250)->nullable();
			$table->float('unit', 10, 0)->nullable()->default(0);
			$table->float('price_unit', 10, 0)->nullable()->default(0);
			$table->string('event_detail_id', 64)->nullable();
			$table->integer('event_id')->nullable();
			$table->string('teacher_id', 64)->nullable();
			$table->string('student_id', 64)->nullable();
			$table->smallInteger('participation_id')->nullable()->default(10);
			$table->string('price_type_id', 50)->nullable();
			$table->float('price', 10, 0)->nullable()->default(0);
			$table->float('total_item', 10, 0)->nullable();
			$table->string('price_currency', 3)->nullable();
			$table->string('unit_type', 20)->nullable();
			$table->float('event_extra_expenses', 10, 0)->nullable()->default(0);
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
		Schema::drop('invoice_items');
	}

}
