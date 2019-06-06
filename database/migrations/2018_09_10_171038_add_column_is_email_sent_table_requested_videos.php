<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsEmailSentTableRequestedVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requested_videos', function (Blueprint $table) {
            $table->tinyInteger('is_email_sent')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requested_videos', function (Blueprint $table) {
            $table->dropColumn('is_email_sent');
        });
    }
}
