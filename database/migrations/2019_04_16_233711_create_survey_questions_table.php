<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text', 191);
            $table->string('type', 191);
            $table->integer('survey_id')->unsigned()->index('survey_questions_survey_id_foreign');
            $table->timestamps();
            $table->string('language', 12)->nullable();
            $table->integer('tnid')->nullable()->comment('translation id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('survey_questions');
    }
};
