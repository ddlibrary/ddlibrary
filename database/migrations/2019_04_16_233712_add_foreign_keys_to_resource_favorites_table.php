<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResourceFavoritesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resource_favorites', function(Blueprint $table)
		{
			$table->foreign('resource_id', 'resource_favorites_ibfk_1')->references('id')->on('resources')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'resource_favorites_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resource_favorites', function(Blueprint $table)
		{
			$table->dropForeign('resource_favorites_ibfk_1');
			$table->dropForeign('resource_favorites_ibfk_2');
		});
	}

}
