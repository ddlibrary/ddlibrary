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
        Schema::create('resource_translation_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('resource_id');
            $table->unsignedInteger('link_resource_id');
            $table->string('language');

            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->onDelete('CASCADE');

            $table->foreign('link_resource_id')
                ->references('id')
                ->on('resources')
                ->onDelete('CASCADE');

            $table->unique(['resource_id', 'link_resource_id']);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_translation_links');
    }
};
