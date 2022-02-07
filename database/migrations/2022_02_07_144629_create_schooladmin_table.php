<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchooladminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schooladmins', function (Blueprint $table) {
            $table->id();
			$table->integer('school_id');
			$table->integer('visibility_id')->nullable()->default(10);
			$table->integer('gender_id')->nullable();
			$table->string('lastname', 250)->nullable();
			$table->string('middlename', 250)->nullable();
			$table->string('firstname', 250)->nullable();
			$table->dateTime('birth_date')->nullable();
			$table->string('phone', 50)->nullable();
			$table->string('mobile', 50)->nullable();
			$table->string('email', 50)->nullable();
			$table->string('street', 120)->nullable();
			$table->string('street_number', 20)->nullable();
			$table->string('street2', 100)->nullable();
			$table->string('zip_code', 8)->nullable();
			$table->string('place', 120)->nullable();
			$table->integer('country_id')->nullable();
			$table->integer('province_id')->nullable();
			$table->float('geo_latitude', 10, 0)->nullable();
			$table->float('geo_longitude', 10, 0)->nullable();
			$table->boolean('type')->nullable()->default(1)->comment('1= teacher, 2= coach');
			$table->string('comment', 500)->nullable();
			$table->integer('profile_image_id')->nullable();
			$table->smallInteger('has_user_account')->nullable()->default(0);
			$table->text('teacher_cv')->nullable();
			$table->string('licence_arp', 30)->nullable();
			$table->string('licence_usp', 30)->nullable();
			$table->string('licence_js', 30)->nullable();
			$table->string('bank_iban', 50)->nullable();
			$table->string('bank_account', 30)->nullable();
			$table->string('bank_swift', 10)->nullable();
			$table->string('bank_name', 120)->nullable();
			$table->string('bank_address', 100)->nullable();
			$table->string('bank_zipcode', 10)->nullable();
			$table->string('bank_place', 100)->nullable();
			$table->string('bank_country_id', 2)->nullable();
			$table->integer('bank_province_id')->nullable();
			$table->string('bg_color_agenda', 7)->nullable();
			$table->string('billing_street', 120)->nullable();
			$table->string('billing_street_number', 30)->nullable();
			$table->string('billing_street2', 120)->nullable();
			$table->string('billing_zip_code', 10)->nullable();
			$table->string('billing_place', 120)->nullable();
			$table->string('billing_country_id', 2)->nullable();
			$table->integer('billing_province_id')->nullable();
			$table->string('about_text', 500)->nullable();
			$table->integer('display_home_flag')->nullable()->default(1);
			$table->string('billing_method', 3)->nullable()->default('E')->comment('E=Eventwise, M=Monthly & Y=Yearly');
			$table->float('billing_amount', 10, 0)->nullable()->default(0);
			$table->dateTime('billing_method_eff_date')->nullable()->comment('for information only ');
			$table->string('billing_currency', 3)->nullable()->comment('currency for billing');
			$table->date('billing_date_start')->nullable()->comment('billing start date');
			$table->date('billing_date_end')->nullable()->comment('billing end date');
			$table->integer('invoice_process_day_no')->nullable();
			$table->string('person_language_preference', 3)->nullable()->default('fr');
			$table->string('tax_desc', 150)->nullable();
			$table->decimal('tax_perc', 10)->nullable();
			$table->boolean('tax_applicable')->nullable()->default(0);
			$table->string('etransfer_acc', 100)->nullable()->default('');
			$table->string('cheque_payee', 100)->nullable()->default('');
			$table->string('tax_number', 100)->nullable()->default('');
			$table->smallInteger('invoice_opt_activated')->nullable()->default(0);
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
        Schema::dropIfExists('schooladmins');
    }
}
