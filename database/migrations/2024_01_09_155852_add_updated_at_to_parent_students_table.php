<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedAtToParentStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parent_students', function (Blueprint $table) {
            $table->timestamps(); // Ceci ajoutera les colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parent_students', function (Blueprint $table) {
            $table->dropTimestamps(); // Ceci supprimera les colonnes created_at et updated_at
        });
    }
}
