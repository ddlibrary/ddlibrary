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
        Schema::create('resource_files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('taxonomy_term_data_id')->nullable(); // TaxonomyTerm Model - License
            $table->string('path', 500);
            $table->string('height')->nullable();
            $table->string('width')->nullable();
            $table->string('size')->nullable();
            $table->string('language', 5)->nullable()->index();
            $table->string('thumbnail_path', 500)->nullable();
            
            $table->foreign('taxonomy_term_data_id')->references('id')->on('taxonomy_term_data');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_files');
    }
};
