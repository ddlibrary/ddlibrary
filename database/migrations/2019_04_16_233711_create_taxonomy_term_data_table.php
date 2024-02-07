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
        Schema::create('taxonomy_term_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vid')->unsigned()->default(0);
            $table->string('name')->default('')->index('name');
            $table->integer('weight')->default(0)->comment('The weight of this term in relation to other terms.');
            $table->string('language', 12)->default('und');
            $table->integer('tnid')->nullable()->default(0);
            $table->index(['vid', 'weight', 'name'], 'taxonomy_tree');
            $table->index(['vid', 'name'], 'vid_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('taxonomy_term_data');
    }
};
