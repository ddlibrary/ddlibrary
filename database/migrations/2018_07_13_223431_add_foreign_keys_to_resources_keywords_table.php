<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResourcesKeywordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resources_keywords', function(Blueprint $table)
		{
			$table->foreign('resourceid', 'resources_keywords_ibfk_1')->references('resourceid')->on('resources')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resources_keywords', function(Blueprint $table)
		{
			$table->dropForeign('resources_keywords_ibfk_1');
		});
	}

}
