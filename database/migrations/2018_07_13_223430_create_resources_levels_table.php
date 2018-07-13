<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesLevelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_levels', function(Blueprint $table)
		{
			$table->increments('levelid');
			$table->integer('resourceid')->unsigned()->index('resourceid');
			$table->integer('resource_level_tid')->unsigned()->nullable()->index('resource_level');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_levels');
	}

}
