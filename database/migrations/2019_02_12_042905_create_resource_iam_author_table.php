<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourceIamAuthorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resource_iam_author', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('resource_id')->unsigned()->nullable()->index('resources_iam_author_ibfk_1');
			$table->integer('value')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resource_iam_author');
	}

}
