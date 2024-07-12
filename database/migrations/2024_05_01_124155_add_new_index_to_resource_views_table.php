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
            $table->index(['resource_id', 'user_id']);
            $table->index(['resource_id', 'created_at']);
            $table->index(['resource_id', 'user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_views', function (Blueprint $table) {
            $table->dropIndex(['resource_id', 'user_id']);
            $table->dropIndex(['resource_id', 'created_at']);
            $table->dropIndex(['resource_id', 'user_id', 'created_at']);
        });
    }
};
