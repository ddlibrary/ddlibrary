<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaticOccupationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('static_occupations', function(Blueprint $table)
		{
			$table->increments('occupationid');
			$table->string('name_en')->nullable();
			$table->string('name_fa')->nullable();
			$table->string('name_pa')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('static_occupations');
	}

}
