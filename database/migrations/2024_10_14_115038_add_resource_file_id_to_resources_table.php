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
            $table->unsignedBigInteger('resource_file_id')->after('user_id')->nullable();
            $table->string('image', 500)->after('title')->nullable();

            $table->foreign('resource_file_id')->references('id')->on('resource_files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropForeign(['resource_file_id']);
            $table->dropColumn('resource_file_id');
            $table->dropColumn('image');
        });
    }
};
