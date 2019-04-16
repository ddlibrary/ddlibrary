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
			$table->increments('id');
			$table->string('username', 60)->default('');
			$table->string('password')->default('');
			$table->string('email', 254)->default('')->comment('User’s e-mail address.');
			$table->boolean('status')->nullable()->default(0)->index('status');
			$table->string('language', 12)->nullable()->default('en');
			$table->string('remember_token', 100)->nullable();
			$table->dateTime('accessed_at')->nullable()->index('access');
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
		Schema::drop('users');
	}

}
