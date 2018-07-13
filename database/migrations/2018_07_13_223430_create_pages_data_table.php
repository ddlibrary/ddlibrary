<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages_data', function(Blueprint $table)
		{
			$table->increments('pageid');
			$table->string('title')->nullable();
			$table->text('summary')->nullable();
			$table->text('body')->nullable();
			$table->string('language', 12)->nullable();
			$table->integer('tnid')->nullable()->comment('translation id');
			$table->integer('status')->nullable();
			$table->integer('created')->nullable()->index('created');
			$table->integer('updated')->nullable()->index('updated');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pages_data');
	}

}
