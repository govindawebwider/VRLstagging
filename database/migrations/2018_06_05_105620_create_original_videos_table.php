<?php

/**
 * CreateOriginalVideosTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreateOriginalVideosTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOriginalVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('original_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('watermark_video_id', 100);
            $table->string('video_path', 100);
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
        Schema::drop('original_videos');
    }
}
