<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices_expenses', function(Blueprint $table)
		{
			$table->integer('id', true);
            $table->integer('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices');
			$table->string('expense_name')->nullable();
			$table->decimal('expense_amount', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoices_expenses');
	}

}
