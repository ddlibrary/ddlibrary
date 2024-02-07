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
        Schema::create('resource_creative_commons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned()->nullable()->index();
            $table->integer('tid')->unsigned()->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('resource_creative_commons');
    }
};
