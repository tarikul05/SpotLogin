<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) { 
            $table->integer('student_is_paying')->nullable();
            $table->string('event_invoice_type')->nullable()->nullable()->default('T');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('student_is_paying');
            $table->dropColumn('event_invoice_type');
        }); 
    }
}
