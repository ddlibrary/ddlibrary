<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResourceEducationalResourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resource_educational_resources', function(Blueprint $table)
		{
			$table->foreign('resource_id', 'resource_educational_resources_ibfk_1')->references('id')->on('resources')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resource_educational_resources', function(Blueprint $table)
		{
			$table->dropForeign('resource_educational_resources_ibfk_1');
		});
	}

}
