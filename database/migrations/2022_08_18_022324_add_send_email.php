<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_teacher', function (Blueprint $table) {
            $table->boolean('send_email')->nullable()->default(0)->after('school_id');
        });
        Schema::table('school_student', function (Blueprint $table) {
            $table->boolean('send_email')->nullable()->default(0)->after('school_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_teacher', function (Blueprint $table) {
            $table->dropColumn('send_email');
        });
        Schema::table('school_student', function (Blueprint $table) {
            $table->dropColumn('send_email');
        });
    }
}
