<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('person_id')->default(0);
            $table->string('person_type',50)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('password', 120)->default('d54d1702ad0f8326224b817c796763c9');
            $table->integer('status')->default(10);
            $table->string('firstname', 120)->nullable();
            $table->string('lastname', 120)->nullable();
            $table->smallInteger('gender_id')->nullable();
            $table->dateTime('birth_date')->nullable();
            $table->integer('is_mail_sent')->default(0);
            $table->integer('is_reset_mail_requested')->default(0);
            $table->string('profile_image_id', 64)->nullable();
            $table->string('username', 120)->nullable();
            $table->string('user_authorisation', 3)->default('MIN');
            $table->string('school_id', 64)->default('FCDB0ADF-C49F-4D8A-B244-DF6B58104DA3');
            $table->boolean('is_active')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
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
        Schema::dropIfExists('users');
    }
}
