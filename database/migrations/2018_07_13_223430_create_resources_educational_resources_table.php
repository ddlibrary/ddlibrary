<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesEducationalResourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_educational_resources', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('resourceid')->unsigned()->nullable()->index('resourceid');
			$table->integer('educational_resource')->nullable()->comment('0: not published, 1: published');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_educational_resources');
	}

}
