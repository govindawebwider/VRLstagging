<?php

/**
 * CreateSlidersTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreateSlidersTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('slider_visibility');
            $table->string('slider_title', 100);
            $table->string('slider_description', 500);
            $table->string('mob_slider_desc', 20);
            $table->string('slider_path', 100);
            $table->string('mob_slider_path', 100);
            $table->integer('artist_id');
            $table->double('rate')->nullble();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sliders');
    }
}
