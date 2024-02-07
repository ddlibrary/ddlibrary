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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 60)->default('');
            $table->string('password')->default('');
            $table->string('email', 254)->default('')->comment('User’s e-mail address.');
            $table->boolean('status')->nullable()->default(0);
            $table->string('language', 12)->nullable()->default('en');
            $table->string('remember_token', 100)->nullable();
            $table->dateTime('accessed_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('users');
    }
};
