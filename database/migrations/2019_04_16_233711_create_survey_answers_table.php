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
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip', 191);
            $table->text('description', 65535)->nullable();
            $table->integer('question_id')->unsigned()->index('survey_answers_question_id_foreign');
            $table->integer('answer_id')->unsigned()->nullable()->index('survey_answers_answer_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('survey_answers');
    }
};
