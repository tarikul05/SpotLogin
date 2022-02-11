<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTaxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices_taxes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('invoice_id');
			$table->string('tax_name')->nullable();
			$table->decimal('tax_percentage', 10)->nullable();
			$table->string('tax_number', 100)->nullable();
			$table->decimal('tax_amount', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoices_taxes');
	}

}
