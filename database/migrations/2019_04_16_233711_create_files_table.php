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
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('size')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->integer('updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('files');
    }
};
