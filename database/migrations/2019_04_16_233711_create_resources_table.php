<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->text('abstract', 65535)->nullable();
			$table->string('language', 12)->nullable();
			$table->integer('user_id')->unsigned()->default(0)->index('userid');
			$table->integer('status')->nullable()->default(0)->index('status')->comment('0: not published, 1: published');
			$table->integer('tnid')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources');
	}

}
