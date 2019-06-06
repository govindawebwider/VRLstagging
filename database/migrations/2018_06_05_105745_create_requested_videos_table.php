<?php

/**
 * CreateRequestedVideosTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreateRequestedVideosTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestedVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requested_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('request_id');
            $table->text('description')->nullable();
            $table->unsignedInteger('requestby');
            $table->unsignedInteger('uploadedby');
            $table->string('url', 100);
            $table->string('desti_url', 1000);
            $table->string('thumbnail');
            $table->string('desti_thumbnail', 400);
            $table->string('thumbnail_status', 20);
            $table->unsignedInteger('is_active');
            $table->string('fileName', 20);
            $table->date('Upload_date');
            $table->date('purchase_date');
            $table->string('remain_storage_duration', 20);
            $table->string('token')->nullable();
            $table->tinyInteger('token_used')->default(0);
            $table->tinyInteger('deleted_from_artist')->default(0);
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
        Schema::drop('requested_videos');
    }
}
