<?php

/**
 * AddRejectedReasonCommentInRequestvideosTable Migration
 *
 * @class AddRejectedReasonCommentInRequestvideosTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectedReasonCommentInRequestvideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requestvideos', function (Blueprint $table) {
            $table->unsignedTinyInteger('rejected_reason')->nullable();
            $table->string('rejected_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requestvideos', function (Blueprint $table) {
            //
        });
    }
}
