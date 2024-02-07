<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_roles', function (Blueprint $table) {
            $table->foreign('role_id', 'user_roles_ibfk_2')->references('id')->on('roles')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('user_id', 'user_roles_ibfk_3')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_roles', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('user_roles_ibfk_2');
                $table->dropForeign('user_roles_ibfk_3');
            }
        });
    }
};
