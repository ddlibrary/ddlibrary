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
        Schema::table('taxonomy_term_data', function (Blueprint $table) {
            $table->boolean('excluded')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taxonomy_term_data', function (Blueprint $table) {
            $table->dropColumn('excluded');
        });
    }
};
