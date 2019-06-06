<?php

/**
* AddVideoidToReactionvideos Migration
*
* @class AddVideoidToReactionvideos
*
* @author Abhishek Tandon <abhishek.tandon@surmountsoft.in>
*
* @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
*/


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVideoidToReactionvideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reactionvideos', function (Blueprint $table) {
            $table->unsignedInteger('requested_video_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reactionvideos', function (Blueprint $table) {
            //
        });
    }
}
