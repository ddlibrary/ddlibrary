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
        Schema::create('resource_translation_rights', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned()->nullable()->index();
            $table->integer('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('resource_translation_rights');
    }
};
