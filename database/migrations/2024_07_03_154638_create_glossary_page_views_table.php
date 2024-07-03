<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('glossary_page_views', function (Blueprint $table) {
            $table->id();
            $table->string('page_url', 255);
            $table->string('user_agent', 255);
            $table->string('ip_address', 45);
            $table->string('browser', 45);
            $table->string('title')->nullable();
            
            $table->boolean('is_bot')->default(false);
            $table->string('language', 4);
            $table->string('gender', 11)->index()->nullable();
            $table->foreignId('device_id')->constrained('devices');
            $table->foreignId('platform_id')->constrained('platforms');
            $table->foreignId('browser_id')->constrained('browsers');
            $table->tinyInteger('status')->comment("1: view, 2: created");
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('glossary_id');

            $table->foreign('user_id')
            ->references('id')
            ->on('users');

            $table->foreign('glossary_id')
            ->references('id')
            ->on('glossary');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glossary_page_views');
    }
};
