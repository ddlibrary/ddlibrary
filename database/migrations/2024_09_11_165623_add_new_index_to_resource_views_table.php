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
        Schema::table('resource_views', function (Blueprint $table) {
            $table->index(['is_bot', 'user_id']);
            $table->index(['is_bot', 'resource_id']);
            $table->index(['is_bot', 'resource_id', 'created_at']);
            $table->index(['created_at', 'is_bot']);
            $table->index(['created_at', 'is_bot','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_views', function (Blueprint $table) {
            $table->dropIndex(['is_bot', 'user_id']);
            $table->dropIndex(['is_bot', 'resource_id']);
            $table->dropIndex(['is_bot', 'resource_id', 'created_at']);
            $table->dropIndex(['created_at', 'is_bot']);
            $table->dropIndex(['created_at', 'is_bot','user_id']);
        });
    }
};
