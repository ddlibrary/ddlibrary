<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeaturedResourceLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featured_resource_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fcid')->unsigned()->nullable();
            $table->integer('level_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('featured_resource_levels');
    }
}
