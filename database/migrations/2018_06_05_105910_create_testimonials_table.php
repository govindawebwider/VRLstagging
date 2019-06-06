<?php

/**
 * CreateTestimonialsTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreateTestimonialsTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestimonialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('by_profile_id');
            $table->integer('to_profile_id');
            $table->integer('video_id');
            $table->string('TestimonyData')->nullable();
            $table->string('AdminApproval');
            $table->string('Message', 2000);
            $table->string('testi_date', 20)->nullable();
            $table->unsignedInteger('is_active');
            $table->string('user_name', 20);
            $table->string('Email', 100);
            $table->string('to_artist', 50)->nullable();
            $table->unsignedInteger('is_default')->nullable();
            $table->integer('show_home')->nullable();
            $table->integer('show_artist')->nullable();
            $table->double('rate')->nullable();
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
        Schema::drop('testimonials');
    }
}
