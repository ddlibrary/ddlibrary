<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeaturedResourceTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('featured_resource_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('fcid')->unsigned()->nullable();
			$table->integer('type_id')->unsigned()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('featured_resource_types');
	}

}
