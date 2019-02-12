<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourceLearningResourceTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resource_learning_resource_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('resource_id')->unsigned()->index('resourceid');
			$table->integer('tid')->unsigned()->nullable()->index('learning_resource_type');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resource_learning_resource_types');
	}

}
