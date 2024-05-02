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
        Schema::table('download_counts', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('resource_id');
            $table->index(['resource_id', 'file_id']);
            $table->index(['resource_id', 'file_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('download_counts', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['resource_id']);
            $table->dropIndex(['resource_id', 'file_id']);
            $table->dropIndex(['resource_id', 'file_id', 'created_at']);
        });
    }
};
