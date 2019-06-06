<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArtistReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artist_report', function (Blueprint $table) {
            $table->increments('report_id');
            $table->integer('by')->nullable();
            $table->integer('for')->nullable();
            $table->unsignedTinyInteger('report_type');
            $table->string('comment')->nullable();
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
        Schema::drop('artist_report');
    }
}
