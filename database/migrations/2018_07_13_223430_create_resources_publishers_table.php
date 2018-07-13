<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesPublishersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_publishers', function(Blueprint $table)
		{
			$table->increments('publisherid');
			$table->integer('resourceid')->unsigned()->index('resourceid');
			$table->integer('publisher_name_tid')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_publishers');
	}

}
