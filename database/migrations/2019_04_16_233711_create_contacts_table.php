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
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('email', 191);
            $table->string('subject', 191);
            $table->text('message', 16777215);
            $table->integer('isread')->nullable()->default(0)->comment('0: unread, 1: read');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('contacts');
    }
};
