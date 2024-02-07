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
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('location')->nullable();
            $table->string('path')->nullable();
            $table->integer('parent')->nullable()->default(0)->index();
            $table->string('language', 12)->nullable();
            $table->integer('weight')->nullable()->index('weight');
            $table->integer('tnid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('menus');
    }
};
