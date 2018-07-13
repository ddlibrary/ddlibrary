<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('resourceid')->unsigned()->nullable();
			$table->integer('userid')->unsigned()->nullable();
			$table->text('comment', 65535)->nullable();
			$table->integer('status')->nullable()->default(0)->comment('0: not published, 1: published');
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
		Schema::drop('resources_comments');
	}

}
