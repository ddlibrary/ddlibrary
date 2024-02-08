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
        Schema::create('featured_resource_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fcid')->unsigned()->nullable();
            $table->integer('subject_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('featured_resource_subjects');
    }
};
