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
        Schema::create('resource_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned()->index();
            $table->string('file_name')->nullable();
            $table->string('file_mime')->nullable();
            $table->string('file_size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('resource_attachments');
    }
};
