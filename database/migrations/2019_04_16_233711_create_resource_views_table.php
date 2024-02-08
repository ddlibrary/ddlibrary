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
        Schema::create('resource_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned()->default(0)->index('nid');
            $table->integer('user_id')->default(0);
            $table->string('ip', 32)->default('')->index('ip');
            $table->string('browser_name', 64)->nullable()->default('');
            $table->string('browser_version', 64)->nullable()->default('');
            $table->string('platform', 64)->nullable()->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('resource_views');
    }
};
