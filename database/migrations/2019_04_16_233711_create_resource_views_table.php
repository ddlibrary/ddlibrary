<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourceViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned()->default(0)->index('nid');
            $table->integer('user_id')->default(0);
            $table->string('ip', 32)->default('')->index('ip');
            $table->string('browser_name', 64)->nullable()->default('');
            $table->string('browser_version', 64)->nullable()->default('');
            $table->string('platform', 64)->nullable()->default('');
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
        Schema::drop('resource_views');
    }
}
