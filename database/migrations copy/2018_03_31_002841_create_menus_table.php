<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menus', function(Blueprint $table)
		{
			$table->increments('menuid');
			$table->integer('parent')->nullable()->index('parent');
			$table->string('title', 11)->nullable();
			$table->integer('weight')->nullable()->index('weight');
			$table->string('language', 12)->nullable();
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
		Schema::drop('menus');
	}

}
