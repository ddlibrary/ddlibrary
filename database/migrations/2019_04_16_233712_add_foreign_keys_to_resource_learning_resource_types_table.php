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
        Schema::table('resource_learning_resource_types', function (Blueprint $table) {
            $table->foreign('resource_id', 'resource_learning_resource_types_ibfk_1')->references('id')->on('resources')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('tid', 'resource_learning_resource_types_ibfk_2')->references('id')->on('taxonomy_term_data')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_learning_resource_types', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('resource_learning_resource_types_ibfk_1');
                $table->dropForeign('resource_learning_resource_types_ibfk_2');
            }
        });
    }
};
