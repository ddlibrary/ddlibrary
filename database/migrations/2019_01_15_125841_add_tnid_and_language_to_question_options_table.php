<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTnidAndLanguageToQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_question_options', function (Blueprint $table) {
            $table->string('language', 12)->nullable();
            $table->integer('tnid')->nullable()->comment('translation id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_question_options', function (Blueprint $table) {
            $table->dropColumn('language');
            $table->dropColumn('tnid');
        });
    }
}
