<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentToCalendarSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calendar_settings', function (Blueprint $table) {
            $table->tinyInteger('current')->default(0); // Crée la colonne current de type TINYINT
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calendar_settings', function (Blueprint $table) {
            $table->dropColumn('current'); // Supprime la colonne current si la migration est annulée
        });
    }
}
