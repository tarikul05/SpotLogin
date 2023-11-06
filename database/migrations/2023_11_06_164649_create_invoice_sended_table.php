<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceSendedTable extends Migration
{
    public function up()
    {
        Schema::create('invoice_sended', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('student_id');
            // Ajoutez d'autres colonnes au besoin

            // Contraintes de clé étrangère
            $table->foreign('invoice_id')->references('id')->on('invoices')->unsigned(); // Assurez-vous de spécifier le type de données ici

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('student_id')->references('id')->on('students');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_sended');
    }
}
