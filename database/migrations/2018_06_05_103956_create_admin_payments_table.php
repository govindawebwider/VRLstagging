<?php

/**
 * CreateAdminPaymentsTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreateAdminPaymentsTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id');
            $table->string('status');
            $table->integer('payment_to');
            $table->string('paid_amount', 255);
            $table->integer('video_request_id');
            $table->string('date', 255);
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
        Schema::drop('admin_payments');
    }
}
