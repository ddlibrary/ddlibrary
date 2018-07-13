<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesSubjectAreasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_subject_areas', function(Blueprint $table)
		{
			$table->increments('subjectareaid');
			$table->integer('resourceid')->unsigned()->index('resourceid');
			$table->integer('subject_area_tid')->unsigned()->nullable()->index('subject_area');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_subject_areas');
	}

}
