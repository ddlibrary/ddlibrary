<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_data', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('resourceid')->unsigned()->primary();
			$table->string('title')->nullable();
			$table->text('abstract', 65535)->nullable();
			$table->string('language', 12)->nullable();
			$table->integer('userid')->unsigned()->default(0)->index('userid');
			$table->integer('status')->nullable()->default(0)->index('status')->comment('0: not published, 1: published');
			$table->integer('tnid')->nullable();
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
		Schema::drop('resources_data');
	}

}
