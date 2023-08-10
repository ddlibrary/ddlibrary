<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSurveyAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('survey_answers', function(Blueprint $table)
		{
			$table->foreign('answer_id')->references('id')->on('survey_question_options')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
		Schema::table('survey_answers', function(Blueprint $table)
		{
			if (DB::getDriverName() !== 'sqlite') {
				$table->dropForeign('survey_answers_answer_id_foreign');
				$table->dropForeign('survey_answers_question_id_foreign');
			}
			
		});
	}

}
