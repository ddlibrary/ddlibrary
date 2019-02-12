<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourceLevelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resource_levels', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('resource_id')->unsigned()->index('resourceid');
			$table->integer('tid')->unsigned()->nullable()->index('resource_level_tid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resource_levels');
	}

}
