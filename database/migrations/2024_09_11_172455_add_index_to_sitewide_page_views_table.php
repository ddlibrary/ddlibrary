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
        Schema::table('sitewide_page_views', function (Blueprint $table) {
            $table->index(['is_bot']);
            $table->index(['created_at']);
            $table->index(['is_bot', 'browser_id', 'created_at']);
            $table->index(['is_bot', 'platform_id', 'created_at']);
            $table->index(['is_bot', 'gender', 'created_at']);
            $table->index(['is_bot', 'created_at']);
            $table->index(['is_bot', 'created_at','user_id']);
            $table->index(['is_bot','page_url', 'title']);
            $table->index(['is_bot', 'created_at','page_url', 'title']);
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sitewide_page_views', function (Blueprint $table) {
            $table->dropIndex(['is_bot']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['is_bot', 'browser_id', 'created_at']);
            $table->dropIndex(['is_bot', 'platform_id', 'created_at']);
            $table->dropIndex(['is_bot', 'gender', 'created_at']);
            $table->dropIndex(['is_bot', 'created_at']);
            $table->dropIndex(['is_bot', 'created_at','user_id']);
            $table->dropIndex(['is_bot','page_url', 'title']);
            $table->dropIndex(['is_bot','created_at','page_url', 'title']);
        });
    }
};
