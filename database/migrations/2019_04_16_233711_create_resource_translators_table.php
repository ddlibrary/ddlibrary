<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resource_translators', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned()->index();
            $table->integer('tid')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('resource_translators');
    }
};
