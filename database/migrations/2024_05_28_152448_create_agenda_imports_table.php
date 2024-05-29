<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_imports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('date');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('count_students');
            $table->string('students_names');
            $table->string('title');
            $table->string('duration');
            $table->string('coach');
            $table->boolean('imported');
            $table->unsignedBigInteger('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda_imports');
        
    }
}
