<?php

/**
 * AlterSongNameColumnToNullRequestvideosTable Migration
 *
 * @class AlterSongNameColumnToNullRequestvideosTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSongNameColumnToNullRequestvideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requestvideos', function (Blueprint $table) {
            $table->string('song_name', 100)->nullable()->change();
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
