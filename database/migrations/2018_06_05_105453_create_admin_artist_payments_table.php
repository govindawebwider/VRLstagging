<?php

/**
 * CreateAdminArtistPaymentsTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreateAdminArtistPaymentsTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminArtistPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_artist_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id', 500);
            $table->string('status', 255);
            $table->integer('payment_to');
            $table->string('artist_name', 50);
            $table->string('paid_amount', 255);
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
        Schema::drop('admin_artist_payments');
    }
}
