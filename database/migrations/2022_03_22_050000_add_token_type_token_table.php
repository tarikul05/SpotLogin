<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTokenTypeTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verify_token', function (Blueprint $table) {
            $table->integer('school_id')>after('id');
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('person_id')->default(0)->after('id');
            $table->string('person_type',50)->nullable()->after('id');
            $table->enum('token_type', ['ACTIVATION','FORGOT_PASSWORD','VERIFY_SIGNUP'])->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verify_token', function (Blueprint $table) {
            $table->dropColumn('person_id');
            $table->dropColumn('person_type');
            $table->dropColumn('token_type');
        });
    }
}
