<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyInvoiceSetupRunday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_invoice_setup_runday', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->integer('day_no')->nullable()->default(1);
            $table->integer('active_flag')->nullable()->default(0)->comment('0=false wont run, 1 = true will run');
            $table->string('language_preference', 3)->nullable();
            $table->timestamp('lastrun_start_time')->nullable();
            $table->timestamp('lastrun_end_time')->nullable();
            $table->string('process_month',2)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_invoice_setup_runday');
    }
}
