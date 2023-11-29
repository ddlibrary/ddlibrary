<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisitedStoryweaverDisclaimerToUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->boolean('visited_storyweaver_disclaimer')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('visited_storyweaver_disclaimer');
        });
    }
}
