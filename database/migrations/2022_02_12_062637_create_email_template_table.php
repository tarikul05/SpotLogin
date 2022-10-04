<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_template', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('template_code', 100);
            $table->string('template_name', 2000)->nullable();
            $table->longText('subject_text')->nullable();
            $table->longText('body_text')->nullable();
            $table->string('language', 10);
            $table->string('is_active', 1)->nullable()->default('1');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('modified_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('created_by');
            $table->integer('modified_by')->nullable();
            $table->softDeletes();

            $table->unique(['template_code', 'language'], 'template_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_template');
    }
}
