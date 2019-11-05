<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGlossaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('glossary', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('name_en', 65535)->nullable();
			$table->text('name_fa', 65535)->nullable();
			$table->text('name_ps', 65535)->nullable();
			$table->string('subject')->nullable();
			$table->integer('user_id')->nullable();
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
		Schema::drop('glossary');
	}

}
