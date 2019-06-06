<?php

/**
 * CreateSocialMediaTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreateSocialMediaTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('social_name',20);
            $table->string('social_img', 100);
            $table->string('social_url', 100);
            $table->integer('addBy_profileId');
            $table->string('is_active', 20);
            $table->string('_token', 20);
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
        Schema::drop('social_media');
    }
}
