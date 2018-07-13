<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesAttachmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources_attachments', function(Blueprint $table)
		{
			$table->increments('attachmentid');
			$table->integer('resourceid')->unsigned()->index('resourceid');
			$table->string('file_name')->nullable();
			$table->string('file_url')->nullable();
			$table->string('file_mime')->nullable();
			$table->string('file_size')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources_attachments');
	}

}
