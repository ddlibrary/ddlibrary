<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourceKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned()->index();
            $table->integer('tid')->unsigned()->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('resource_keywords');
    }
}
