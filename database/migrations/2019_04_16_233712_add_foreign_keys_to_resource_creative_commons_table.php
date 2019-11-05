<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResourceCreativeCommonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resource_creative_commons', function(Blueprint $table)
		{
			$table->foreign('resource_id', 'resource_creative_commons_ibfk_1')->references('id')->on('resources')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('tid', 'resource_creative_commons_ibfk_2')->references('id')->on('taxonomy_term_data')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resource_creative_commons', function(Blueprint $table)
		{
			$table->dropForeign('resource_creative_commons_ibfk_1');
			$table->dropForeign('resource_creative_commons_ibfk_2');
		});
	}

}
