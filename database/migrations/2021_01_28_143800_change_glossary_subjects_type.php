<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('glossary', function (Blueprint $table) {
            $table->foreign('subject')->references('id')->on('glossary_subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('glossary', function (Blueprint $table) {
            $table->string('subject')->change();
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('glossary_subject_foreign');
            }
        });
    }
};
