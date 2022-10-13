<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInvoicesExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices_expenses', function (Blueprint $table) { 
            $table->string('is_active', 1)->nullable()->default('1');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('modified_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('created_by');
            $table->integer('modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
