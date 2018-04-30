<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesEducationalUsesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_educational_uses', function(Blueprint $table)
		{
			$table->increments('eduid');
			$table->integer('resourceid')->unsigned()->index('resourceid');
			$table->string('educational_use')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_educational_uses');
	}

}
