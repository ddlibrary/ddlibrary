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
        Schema::create('taxonomy_term_hierarchy', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('tid')->unsigned();
            $table->integer('parent')->unsigned();
            $table->integer('aux_id')->unsigned()->index();
            $table->unique(['tid', 'parent']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('taxonomy_term_hierarchy');
    }
};
