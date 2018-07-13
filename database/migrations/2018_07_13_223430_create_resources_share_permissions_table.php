<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesSharePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_share_permissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('resourceid')->unsigned()->nullable()->index('resourceid');
			$table->integer('share_permission')->nullable()->comment('0: empty, 1: reproduced with permission, 2:with permission but no translation, 3:with permission but reproduction is restricted, 4: with permission and translation allowed, 5: permission pending, 6: copyrighted, 7: Other, 8: Unknown');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_share_permissions');
	}

}
