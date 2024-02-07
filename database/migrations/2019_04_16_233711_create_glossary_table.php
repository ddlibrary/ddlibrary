<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glossary', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject')->unsigned()->nullable(false);
            $table->text('name_en', 65535)->nullable();
            $table->text('name_fa', 65535)->nullable();
            $table->text('name_ps', 65535)->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::drop('glossary');
    }
};
