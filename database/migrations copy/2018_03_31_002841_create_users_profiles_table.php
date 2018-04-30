<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_profiles', function(Blueprint $table)
		{
			$table->increments('uprofileid');
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('occupation')->nullable();
			$table->string('country')->nullable();
			$table->string('province', 11)->nullable();
			$table->string('gender', 11)->nullable();
			$table->string('first_language')->nullable();
			$table->string('other_languages')->nullable();
			$table->integer('telephone')->nullable()->index('telephone');
			$table->string('organization')->nullable();
			$table->text('age', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_profiles');
	}

}
