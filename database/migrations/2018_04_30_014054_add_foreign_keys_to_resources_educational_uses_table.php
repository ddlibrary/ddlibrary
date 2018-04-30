<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResourcesEducationalUsesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resources_educational_uses', function(Blueprint $table)
		{
			$table->foreign('resourceid', 'resources_educational_uses_ibfk_1')->references('resourceid')->on('resources')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resources_educational_uses', function(Blueprint $table)
		{
			$table->dropForeign('resources_educational_uses_ibfk_1');
		});
	}

}
