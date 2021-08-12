<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->text('summary')->nullable();
			$table->text('body')->nullable();
			$table->string('language', 12)->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('tnid')->nullable()->comment('translation id');
			$table->integer('status')->nullable();
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
		Schema::drop('news');
	}

}
