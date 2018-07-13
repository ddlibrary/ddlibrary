<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDownloadCountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('download_count', function(Blueprint $table)
		{
			$table->increments('dcid')->comment('Primary Key: Unique download count id.');
			$table->integer('fid')->unsigned()->index('dc_fid')->comment('The id from the drupal file_managed table of the file downloaded.');
			$table->integer('uid')->unsigned()->index('dc_uid')->comment('The uid of the user that downloaded the file.');
			$table->string('type', 64)->default('')->index('dc_type')->comment('The name of the entity type to which the file was attached when downloaded.');
			$table->integer('id')->unsigned()->default(0)->index('dc_id')->comment('The primary key of the entity to which the file was attached when downloaded.');
			$table->string('ip_address', 128)->index('dc_ip')->comment('The IP address of the downloading user.');
			$table->text('referrer', 65535)->comment('Referrer URI.');
			$table->integer('timestamp')->unsigned()->default(0)->index('dc_timestamp')->comment('The date-time the file was downloaded.');
			$table->index(['fid','type','id'], 'dc_fid_type_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('download_count');
	}

}
