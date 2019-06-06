<?php

/**
 * CreateRequestvideosTable Migration
 * Migration is created on the basis of received database
 *
 * @class CreateRequestvideosTable
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestvideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requestvideos', function (Blueprint $table) {
            $table->increments('VideoReqId');
            $table->string('Name');
            $table->unsignedInteger('requestToProfileId');
            $table->unsignedInteger('VideoId');
            $table->string('RequestStatus');
            $table->string('DateInterval');
            $table->text('Description')->nullable();
            $table->decimal('ReqVideoPrice', 8, 2);
            $table->dateTime('VideoUploadDate')->nullable();
            $table->string('Title');
            $table->unsignedInteger('requestByProfileId');
            $table->string('requestor_email', 100);
            $table->string('paid', 10)->default('Unpaid');
            $table->integer('is_refunded')->default(0);
            $table->unsignedTinyInteger('is_active');
            $table->unsignedTinyInteger('is_hidden')->default(1);
            $table->string('complitionDate', 20);
            $table->string('request_date', 20);
            $table->string('approval_date', 20)->nullable();
            $table->string('rejected_date', 20)->nullable();
            $table->string('refund_date', 20)->nullable();
            $table->string('song_name', 100);
            $table->string('receipient_pronunciation', 100)->nullable();
            $table->string('sender_name', 50);
            $table->string('sender_name_pronunciation', 100)->nullable();
            $table->string('sender_email', 50);
            $table->string('sender_voice_pronunciation', 50)->nullable();
            $table->string('receipient_voice_pronunciation', 50)->nullable();
            $table->text('recipient_record')->nullable();
            $table->text('sender_record')->nullable();
            $table->string('device_type', 20);
            $table->string('is_delete', 10);
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
        Schema::drop('requestvideos');
    }
}