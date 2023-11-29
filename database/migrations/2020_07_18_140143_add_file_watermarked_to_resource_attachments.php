<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileWatermarkedToResourceAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resource_attachments', function (Blueprint $table) {
            $table->boolean('file_watermarked')
                ->default(false)
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resource_attachments', function (Blueprint $table) {
            $table->dropColumn('file_watermarked');
        });
    }
}
