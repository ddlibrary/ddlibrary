<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaxonomyTermHierarchyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('taxonomy_term_hierarchy', function(Blueprint $table)
		{
			$table->uuid('id')->primary();
			$table->integer('tid')->unsigned();
			$table->integer('parent')->unsigned();
			$table->integer('aux_id')->unsigned()->index();
			$table->unique(['tid', 'parent']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('taxonomy_term_hierarchy');
	}

}
