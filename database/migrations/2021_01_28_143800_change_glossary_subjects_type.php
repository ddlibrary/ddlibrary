<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeGlossarySubjectsType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
    public function down()
    {
        Schema::table('glossary', function (Blueprint $table) {
            $table->string('subject')->change();
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('glossary_subject_foreign');
            }
        });
    }
}
