<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('login');
            $table->string('password');
            $table->integer('status');
//            $table->integer('check');
//            $table->integer('follower');
//            $table->integer('post');
//            $table->string('firstname');
//            $table->string('lastname');
//            $table->date('birthday');
//            $table->string('phone');
//            $table->string('city');
//            $table->string('email');
//            $table->text('comment');
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
        Schema::dropIfExists('blogers');
    }
}
