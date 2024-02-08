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
        Schema::table('resource_attachments', function (Blueprint $table) {
            $table->boolean('file_watermarked')
                ->default(false)
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_attachments', function (Blueprint $table) {
            $table->dropColumn('file_watermarked');
        });
    }
};
