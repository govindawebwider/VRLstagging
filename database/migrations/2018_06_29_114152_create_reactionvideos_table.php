<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateReactionvideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reactionvideos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('UserId')->nullable();
            $table->dateTime('VideoUploadDate')->nullable();
            $table->string('VideoName')->nullable();
            $table->string('VideoFormat')->nullable();
            $table->string('VideoURL')->nullable();
            $table->string('thumbnail')->nullable();
            $table->unsignedTinyInteger('type')->nullable();
            $table->string('type_id')->nullable();  
            $table->unsignedTinyInteger('status')->default(0);
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
        Schema::drop('reactionvideos');
    }
}