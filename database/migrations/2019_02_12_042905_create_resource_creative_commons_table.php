<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourceCreativeCommonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resource_creative_commons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('resource_id')->unsigned()->nullable()->index('resources_creative_commons_ibfk_1');
			$table->integer('tid')->nullable()->comment('0: empty 1: by-sa, 2: by-nc-sa, 3: by-nc-nd, 4: public domain');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resource_creative_commons');
	}

}
