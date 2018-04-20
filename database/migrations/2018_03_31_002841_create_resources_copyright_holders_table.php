<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesCopyrightHoldersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_copyright_holders', function(Blueprint $table)
		{
			$table->increments('chid');
			$table->integer('resourceid')->unsigned()->index('resourceid');
			$table->string('copyright_holder')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_copyright_holders');
	}

}
