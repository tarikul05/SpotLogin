<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_employees', function (Blueprint $table) {
            $table->id();
			// $table->integer('school_id');
            $table->integer('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
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
			$table->string('country_code', 4)->nullable();
            $table->foreign('country_code')->references('code')->on('countries');
			$table->integer('province_id')->nullable();
			$table->float('geo_latitude', 10, 0)->nullable();
			$table->float('geo_longitude', 10, 0)->nullable();
			$table->string('comment', 500)->nullable();
			$table->integer('profile_image_id')->nullable();
			$table->smallInteger('has_user_account')->nullable()->default(0);
			$table->string('about_text', 500)->nullable();
			$table->integer('display_home_flag')->nullable()->default(1);
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
        Schema::dropIfExists('school_employees');
    }
}
