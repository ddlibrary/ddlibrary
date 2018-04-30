<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources', function(Blueprint $table)
		{
			$table->increments('resourceid');
			$table->string('language', 12)->nullable();
			$table->string('title')->nullable();
			$table->integer('userid')->unsigned()->default(0)->index('userid');
			$table->integer('status')->nullable()->default(0)->index('status')->comment('0: not published, 1: published');
			$table->text('abstract', 65535)->nullable();
			$table->integer('translation_rights')->nullable()->default(0)->index('translation_rights')->comment('0: unchecked, 1: checked');
			$table->integer('educational_resource')->nullable()->default(0)->index('educational_resource')->comment('0: unchecked, 1: checked');
			$table->integer('am_author')->nullable()->default(0)->index('am_author');
			$table->integer('creative_commons')->nullable()->default(0)->index('creative_commons')->comment('0: empty 1: by-sa, 2: by-nc-sa, 3: by-nc-nd, 4: public domain');
			$table->integer('permission_share')->nullable()->index('permission_share')->comment('0: empty, 1: reproduced with permission, 2:with permission but no translation, 3:with permission but reproduction is restricted, 4: with permission and translation allowed, 5: permission pending, 6: copyrighted, 7: Other, 8: Unknown');
			$table->integer('created')->nullable();
			$table->integer('updated')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources');
	}

}
