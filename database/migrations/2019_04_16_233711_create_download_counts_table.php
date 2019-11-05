<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDownloadCountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('download_counts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('resource_id')->unsigned()->nullable();
			$table->integer('file_id')->unsigned()->index('dc_fid_type_id')->comment('The id from the drupal file_managed table of the file downloaded.');
			$table->integer('user_id')->unsigned()->nullable()->default(0)->comment('The uid of the user that downloaded the file.');
			$table->string('ip_address')->nullable()->default('')->comment('The IP address of the downloading user.');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('download_counts');
	}

}
