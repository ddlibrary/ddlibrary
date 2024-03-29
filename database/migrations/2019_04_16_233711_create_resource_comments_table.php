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
        Schema::create('resource_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned()->nullable()->index();
            $table->integer('user_id')->unsigned()->nullable();
            $table->text('comment', 65535)->nullable();
            $table->integer('status')->nullable()->default(0)->comment('0: not published, 1: published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('resource_comments');
    }
};
