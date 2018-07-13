<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesTranslatorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_translators', function(Blueprint $table)
		{
			$table->increments('translatorid');
			$table->integer('resourceid')->unsigned()->index('resourceid');
			$table->integer('translator_name_tid')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_translators');
	}

}
