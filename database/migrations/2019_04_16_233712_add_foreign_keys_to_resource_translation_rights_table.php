<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResourceTranslationRightsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resource_translation_rights', function(Blueprint $table)
		{
			$table->foreign('resource_id', 'resource_translation_rights_ibfk_1')->references('id')->on('resources')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resource_translation_rights', function(Blueprint $table)
		{
			$table->dropForeign('resource_translation_rights_ibfk_1');
		});
	}

}
