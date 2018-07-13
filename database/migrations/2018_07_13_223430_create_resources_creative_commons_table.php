<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesCreativeCommonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_creative_commons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('resourceid')->unsigned()->nullable()->index('resourceid');
			$table->integer('creative_commons')->nullable()->comment('0: empty 1: by-sa, 2: by-nc-sa, 3: by-nc-nd, 4: public domain');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_creative_commons');
	}

}
