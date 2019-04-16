<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyQuestionOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('survey_question_options', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('text', 191);
			$table->integer('question_id')->unsigned()->index('survey_question_options_question_id_foreign');
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
	public function down()
	{
		Schema::drop('survey_question_options');
	}

}
