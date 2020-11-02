<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlaggedForReviewToGlossaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('glossary', function (Blueprint $table) {
            $table->boolean('flagged_for_review')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('glossary', function (Blueprint $table) {
            $table->dropColumn('flagged_for_review');
        });
    }
}
