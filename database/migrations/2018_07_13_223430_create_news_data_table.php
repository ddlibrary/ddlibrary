<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news_data', function(Blueprint $table)
		{
			$table->increments('newsid');
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
		Schema::drop('news_data');
	}

}
