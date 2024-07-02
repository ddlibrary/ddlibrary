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
        Schema::table('resources', function (Blueprint $table) {
            $table->index('title');
            $table->index('language');
            $table->index('created_at');
            $table->index(['id', 'language']);
            $table->index(['created_at', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropIndex('title');
            $table->dropIndex('language');
            $table->dropIndex('created_at');
            $table->dropIndex(['id', 'language']);
            $table->dropIndex(['created_at', 'language']);
        });
    }
};
