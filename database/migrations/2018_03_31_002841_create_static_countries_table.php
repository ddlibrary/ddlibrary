<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaticCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('static_countries', function(Blueprint $table)
		{
			$table->increments('countryid');
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
		Schema::drop('static_countries');
	}

}
