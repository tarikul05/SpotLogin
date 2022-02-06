<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schools', function(Blueprint $table)
		{
			$table->id();
			$table->string('school_name', 200)->nullable();
			$table->dateTime('incorporation_date')->nullable();
			$table->string('street', 120)->nullable();
			$table->string('street_number', 20)->nullable();
			$table->string('street2', 100)->nullable();
			$table->string('zip_code', 8)->nullable();
			$table->integer('country_id')->nullable();
			$table->integer('province_id')->nullable();
			$table->string('phone', 50)->nullable();
			$table->string('phone2', 50)->nullable();
			$table->string('mobile', 50)->nullable();
			$table->string('mobile2', 50)->nullable();
			$table->string('email', 50)->nullable();
			$table->string('email2', 50)->nullable();
			$table->integer('logo_image_id')->nullable();
			$table->string('bank_iban', 50)->nullable();
			$table->string('bank_account', 30)->nullable();
			$table->string('bank_swift', 10)->nullable();
			$table->string('bank_name', 120)->nullable();
			$table->string('bank_address', 100)->nullable();
			$table->string('bank_zipcode', 10)->nullable();
			$table->integer('bank_country_id')->nullable();
			$table->integer('bank_province_id')->nullable();
			$table->integer('contact_gender_id')->nullable();
			$table->string('contact_lastname', 120)->nullable();
			$table->string('contact_firstname', 120)->nullable();
			$table->string('contact_position', 120)->nullable();
			$table->string('bank_account_holder', 120)->nullable();
			$table->integer('status')->nullable()->default(10);
			$table->string('school_code', 30)->nullable();
			$table->string('default_currency_code', 10)->nullable()->default('CHF');
			$table->string('sender_email', 200)->nullable();
			$table->string('billing_method', 3)->nullable()->default('M')->comment('E=Eventwise, M=Monthly & Y=Yearly');
			$table->float('billing_amount', 10, 0)->nullable()->default(0);
			$table->dateTime('billing_method_eff_date')->nullable()->comment('for information only ');
			$table->string('billing_currency', 3)->nullable()->comment('currency for billing');
			$table->date('billing_date_start')->nullable()->comment('billing start date');
			$table->date('billing_date_end')->nullable()->comment('billing end date');
			$table->integer('max_students')->nullable()->default(0);
			$table->integer('max_teachers')->nullable()->default(0);
			$table->string('school_type', 1)->nullable()->default('S');
			$table->boolean('tax_applicable')->nullable()->default(0);
			$table->string('tax_desc', 150)->nullable()->default('');
			$table->decimal('tax_perc', 10, 3)->nullable()->default(0.000);
			$table->string('etransfer_acc', 100)->nullable()->default('');
			$table->string('cheque_payee', 100)->nullable()->default('');
			$table->string('tax_number', 100)->nullable();
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
		Schema::drop('schools');
	}

}
