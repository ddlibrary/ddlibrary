<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResourcesDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resources_data', function(Blueprint $table)
		{
			$table->foreign('userid', 'resources_data_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('resourceid', 'resources_data_ibfk_2')->references('resourceid')->on('resources')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resources_data', function(Blueprint $table)
		{
			$table->dropForeign('resources_data_ibfk_1');
			$table->dropForeign('resources_data_ibfk_2');
		});
	}

}
