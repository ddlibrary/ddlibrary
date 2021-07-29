<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaticSubjectAreaIconsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('static_subject_area_icons', function(Blueprint $table)
		{
		    $table->uuid('id');
		    $table->increments('aux_id');
			$table->integer('tid')->unsigned();
			$table->string('file_name')->nullable();
			$table->string('file_url')->nullable();
			$table->string('file_mime')->nullable();
			$table->string('file_size')->nullable();
			$table->index(array('aux_id'));
			$table->dropPrimary();
            $table->primary('tid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('static_subject_area_icons');
	}

}
