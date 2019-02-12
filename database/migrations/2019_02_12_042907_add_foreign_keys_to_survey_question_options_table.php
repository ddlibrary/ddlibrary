<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSurveyQuestionOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('survey_question_options', function(Blueprint $table)
		{
			$table->foreign('question_id')->references('id')->on('survey_questions')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('survey_question_options', function(Blueprint $table)
		{
			$table->dropForeign('survey_question_options_question_id_foreign');
		});
	}

}
