<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaticResourceLevelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('static_resource_levels', function(Blueprint $table)
		{
			$table->increments('slevelid');
			$table->string('name_en')->nullable();
			$table->string('name_fa')->nullable();
			$table->string('name_pa')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('static_resource_levels');
	}

}
