<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('school_id', 64);
			$table->string('invoice_no', 20)->nullable();
			$table->smallInteger('invoice_type')->nullable();
			$table->smallInteger('invoice_status')->nullable();
			$table->timestamp('date_invoice')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('period_starts')->nullable();
			$table->dateTime('period_ends')->nullable();
			$table->dateTime('fully_paid_date')->nullable();
			$table->string('invoice_name', 150)->nullable();
			$table->string('invoice_header', 2000)->nullable();
			$table->string('invoice_footer', 2000)->nullable();
			$table->string('client_id', 64)->nullable();
			$table->string('client_name', 250)->nullable();
			$table->integer('client_gender_id')->nullable();
			$table->string('client_lastname', 250)->nullable();
			$table->string('client_firstname', 250)->nullable();
			$table->string('client_street', 120)->nullable();
			$table->string('client_street_number', 20)->nullable();
			$table->string('client_street2', 100)->nullable();
			$table->string('client_zip_code', 8)->nullable();
			$table->string('client_place', 120)->nullable();
			$table->string('client_country_id', 2)->nullable();
			$table->string('seller_id', 64)->nullable();
			$table->string('seller_name', 250)->nullable();
			$table->integer('seller_gender_id')->nullable();
			$table->string('seller_lastname', 250)->nullable();
			$table->string('seller_firstname', 250)->nullable();
			$table->string('seller_street', 120)->nullable();
			$table->string('seller_street_number', 20)->nullable();
			$table->string('seller_street2', 100)->nullable();
			$table->string('seller_zip_code', 8)->nullable();
			$table->string('seller_place', 120)->nullable();
			$table->string('seller_country_id', 2)->nullable();
			$table->string('seller_phone', 50)->nullable();
			$table->string('seller_mobile', 50)->nullable();
			$table->string('seller_email', 50)->nullable();
			$table->string('seller_eid', 100)->nullable();
			$table->string('payment_bank_account_name', 150)->nullable();
			$table->string('payment_bank_iban', 50)->nullable();
			$table->string('payment_bank_account', 30)->nullable();
			$table->string('payment_bank_swift', 10)->nullable();
			$table->string('payment_bank_name', 120)->nullable();
			$table->string('payment_bank_address', 100)->nullable();
			$table->string('payment_bank_zipcode', 10)->nullable();
			$table->string('payment_bank_place', 100)->nullable();
			$table->string('payment_bank_country_id', 2)->nullable();
			$table->float('subtotal_amount_all', 10, 0)->nullable()->default(0);
			$table->float('subtotal_amount_no_discount', 10, 0)->nullable()->default(0);
			$table->float('subtotal_amount_with_discount', 10, 0)->nullable()->default(0);
			$table->float('discount_percent_1', 10, 0)->nullable()->default(0);
			$table->float('discount_percent_2', 10, 0)->nullable()->default(0);
			$table->float('discount_percent_3', 10, 0)->nullable()->default(0);
			$table->float('discount_percent_4', 10, 0)->nullable()->default(0);
			$table->float('discount_percent_5', 10, 0)->nullable()->default(0);
			$table->float('discount_percent_6', 10, 0)->nullable()->default(0);
			$table->float('amount_discount_1', 10, 0)->nullable()->default(0);
			$table->float('amount_discount_2', 10, 0)->nullable()->default(0);
			$table->float('amount_discount_3', 10, 0)->nullable()->default(0);
			$table->float('amount_discount_4', 10, 0)->nullable()->default(0);
			$table->float('amount_discount_5', 10, 0)->nullable()->default(0);
			$table->float('amount_discount_6', 10, 0)->nullable()->default(0);
			$table->float('total_amount_discount', 10, 0)->nullable()->default(0);
			$table->float('total_amount_no_discount', 10, 0)->nullable()->default(0);
			$table->float('total_amount_with_discount', 10, 0)->nullable()->default(0);
			$table->float('total_vat', 10, 0)->nullable()->default(0);
			$table->float('vat_percent', 10, 0)->nullable()->default(0);
			$table->float('total_amount', 10, 0)->nullable()->default(0);
			$table->string('invoice_filename', 200)->nullable();
			$table->float('extra_expenses', 10, 0)->nullable()->default(0);
			$table->integer('approved_flag')->nullable()->default(0);
			$table->integer('payment_status')->nullable()->default(0)->comment('1=not paid, 1=paid');
			$table->string('invoice_creation_type', 1)->nullable()->default('N');
			$table->string('language_code', 10)->nullable()->default('en');
			$table->string('billing_method', 3)->nullable()->default('E')->comment('E=Eventwise, M=Monthly & Y=Yearly');
			$table->string('invoice_currency', 10)->nullable();
			$table->string('tax_desc', 150)->nullable();
			$table->decimal('tax_perc', 10, 3)->nullable()->default(0.000);
			$table->float('tax_amount', 10, 0)->nullable();
			$table->string('etransfer_acc', 150)->nullable()->default('');
			$table->string('cheque_payee', 150)->nullable()->default('');
			$table->integer('client_province_id')->nullable();
			$table->integer('seller_province_id')->nullable();
			$table->integer('bank_province_id')->nullable();
			$table->string('e_transfer_email', 100)->nullable();
			$table->string('name_for_checks', 100)->nullable();
			$table->string('category_invoiced_type', 1)->nullable()->default('S');
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
		Schema::drop('invoices');
	}

}
