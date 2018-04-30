<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesLearningResourceTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_learning_resource_types', function(Blueprint $table)
		{
			$table->increments('learningid');
			$table->integer('resourceid')->unsigned()->index('resourceid');
			$table->string('learning_resource_type')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_learning_resource_types');
	}

}
