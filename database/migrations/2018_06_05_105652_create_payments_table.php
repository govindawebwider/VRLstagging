<?php

/**
 * CreatePaymentsTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreatePaymentsTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profile_id');
            $table->integer('video_request_id');
            $table->string('stripeToken', 100);
            $table->string('stripeTokenType', 100);
            $table->string('customerEmail', 100);
            $table->string('status', 20);
            $table->integer('is_refunded');
            $table->string('videoPrice', 500);
            $table->string('video_status', 20);
            $table->string('charge_id');
            $table->string('payment_id', 100);
            $table->string('token', 100);
            $table->string('payer_id', 100);
            $table->string('payment_date', 20);
            $table->string('payment_type', 20);
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
        Schema::drop('payments');
    }
}
