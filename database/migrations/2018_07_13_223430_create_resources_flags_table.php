<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesFlagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_flags', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('resourceid')->unsigned()->nullable();
			$table->integer('userid')->unsigned()->nullable();
			$table->integer('type')->nullable()->comment('1: Graphic Violence, 2: Graphic Sexual Content, 3: Spam, Scam or Fraud, 4: Broken or Empty Data');
			$table->text('details', 65535)->nullable();
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
		Schema::drop('resources_flags');
	}

}
