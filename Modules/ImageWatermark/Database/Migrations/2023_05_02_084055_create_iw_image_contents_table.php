<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIwImageContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iw_image_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iw_image_id');
            $table->integer('horizontal')->default(0);
            $table->integer('vertical')->default(0);
            $table->integer('font_size')->default(14);
            $table->boolean('background')->default(0);
            $table->foreign('iw_image_id')->references('id')->on('iw_images');
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
        Schema::table('iw_image_contents', function (Blueprint $table) {
            $table->dropForeign(['iw_image_id']);
        });
        Schema::dropIfExists('iw_image_contents');
    }
}
