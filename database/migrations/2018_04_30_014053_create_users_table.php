<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('userid');
			$table->string('name', 60)->default('');
			$table->string('password')->default('');
			$table->string('email', 254)->nullable()->default('')->comment('User’s e-mail address.');
			$table->boolean('status')->default(0)->index('status');
			$table->string('language', 12)->default('');
			$table->integer('login')->default(0)->index('login');
			$table->integer('access')->default(0)->index('access');
			$table->integer('created')->default(0)->index('created');
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
		Schema::drop('users');
	}

}
