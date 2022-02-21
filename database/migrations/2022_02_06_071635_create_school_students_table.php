<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_students', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->integer('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->string('email', 50)->nullable();
            $table->string('nickname', 50)->nullable();
            $table->string('billing_method', 3)->nullable()->default('E')->comment('E=Eventwise, M=Monthly & Y=Yearly');
            $table->smallInteger('has_user_account')->nullable()->default(0);
            $table->integer('level_id')->nullable();
            $table->foreign('level_id')->references('id')->on('levels');
            $table->string('licence_arp', 30)->nullable();
            $table->string('level_skating_arp', 20)->nullable();
            $table->dateTime('level_date_arp')->nullable();
            $table->string('licence_usp', 30)->nullable();
            $table->string('level_skating_usp', 20)->nullable();
            $table->dateTime('level_date_usp')->nullable();
            $table->string('comment', 500)->nullable();
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
        Schema::dropIfExists('school_students');
    }
}
