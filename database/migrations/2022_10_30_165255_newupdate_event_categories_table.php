<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewupdateEventCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_categories', function (Blueprint $table) { 
            $table->integer('s_std_pay_type')->nullable()->default(0);
            $table->integer('s_thr_pay_type')->nullable()->default(0);
            $table->integer('t_std_pay_type')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_categories', function (Blueprint $table) {
            $table->dropColumn('s_std_pay_type');
            $table->dropColumn('s_thr_pay_type');
            $table->dropColumn('t_std_pay_type');
        });       
    }
}
