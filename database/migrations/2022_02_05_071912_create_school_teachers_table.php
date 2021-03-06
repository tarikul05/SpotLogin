<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_teacher', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->integer('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->string('nickname', 50)->nullable();
            $table->string('licence_js', 100)->nullable();
            $table->string('role_type',30)->nullable()->default('teachers_all')->comment('school_admin, school_employee, teachers_admin, teachers_all, teachers_medium, teachers_minimum');
            $table->boolean('is_teacher')->nullable()->default(0);
            $table->smallInteger('has_user_account')->nullable()->default(0);
            $table->string('bg_color_agenda', 10)->nullable();
            $table->string('comment', 500)->nullable();
            $table->boolean('is_active')->nullable()->default(1);
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
        Schema::dropIfExists('school_teacher');
    }
}
