<?php

/**
 * CreateFooterContentTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreateFooterContentTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFooterContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footer_content', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type');
            $table->text('content')->nullable();
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
        Schema::drop('footer_content');
    }
}
