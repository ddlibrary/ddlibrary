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
			$table->integer('id', true);
			$table->integer('tid')->unsigned()->default(0);
			$table->integer('parent')->unsigned()->default(0)->index('parent');
			$table->primary(['tid','parent']);
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
