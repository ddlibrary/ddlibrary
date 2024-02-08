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
        Schema::create('static_subject_area_icons', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('aux_id');
            $table->integer('tid')->unsigned()->primary();
            $table->string('file_name')->nullable();
            $table->string('file_url')->nullable();
            $table->string('file_mime')->nullable();
            $table->string('file_size')->nullable();
            $table->index(['aux_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('static_subject_area_icons');
    }
};
