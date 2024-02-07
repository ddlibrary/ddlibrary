<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomy_vocabulary', function (Blueprint $table) {
            $table->increments('vid');
            $table->string('name')->default('');
            $table->integer('weight')->default(0)->comment('The weight of this vocabulary in relation to other vocabularies.');
            $table->string('language', 12)->default('und');
            $table->index(['weight', 'name'], 'list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('taxonomy_vocabulary');
    }
};
