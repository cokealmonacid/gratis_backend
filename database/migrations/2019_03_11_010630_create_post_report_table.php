<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_report', function (Blueprint $table) {
            $table->increments('id');
            $table->string('post_id');
            $table->integer('report_id')->unsigned();
            $table->integer('user_id')->unsigned();


            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('report_id')->references('id')->on('report_reasons');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('post_report');
    }
}
