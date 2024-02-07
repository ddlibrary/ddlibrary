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
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('summary')->nullable();
            $table->text('body')->nullable();
            $table->string('language', 12)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('tnid')->nullable()->comment('translation id');
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('news');
    }
};
