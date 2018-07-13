<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeaturedCollectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('featured_collections', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('name_tid')->unsigned()->nullable();
			$table->string('icon')->nullable();
			$table->integer('created')->nullable();
			$table->integer('updated')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('featured_collections');
	}

}
