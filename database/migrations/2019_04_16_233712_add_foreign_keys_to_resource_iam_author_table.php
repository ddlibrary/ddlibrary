<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResourceIamAuthorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resource_iam_author', function(Blueprint $table)
		{
			$table->foreign('resource_id', 'resource_iam_author_ibfk_1')->references('id')->on('resources')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resource_iam_author', function(Blueprint $table)
		{
			$table->dropForeign('resource_iam_author_ibfk_1');
		});
	}

}
