<?php

/**
 * CreateVideocommentsTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreateVideocommentsTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideocommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videocomments', function (Blueprint $table) {
            $table->increments('videoReqComId');
            $table->integer('VideoReqId');
            $table->string('Message');
            $table->string('Name');
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
        Schema::drop('videocomments');
    }
}
