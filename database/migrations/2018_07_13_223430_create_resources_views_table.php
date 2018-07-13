<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesViewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_views', function(Blueprint $table)
		{
			$table->increments('counter_id');
			$table->integer('resourceid')->unsigned()->default(0)->index('nid');
			$table->integer('userid')->default(0);
			$table->string('ip', 32)->default('')->index('ip');
			$table->string('browser_name', 64)->default('');
			$table->string('browser_version', 64)->default('');
			$table->string('platform', 64)->default('');
			$table->integer('created')->default(0)->index('created');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_views');
	}

}
