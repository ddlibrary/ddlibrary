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
    public function up(): void
    {
        Schema::create('resource_educational_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned()->nullable()->index();
            $table->integer('value')->nullable()->comment('0: not published, 1: published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('resource_educational_resources');
    }
};
