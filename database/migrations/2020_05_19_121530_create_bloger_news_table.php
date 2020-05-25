<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogerNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloger_news', function (Blueprint $table) {
            $table->unsignedBigInteger('bloger_id');
            $table->foreign('bloger_id')
                ->references('id')
                ->on('blogers')
                ->onDelete('cascade');
            $table->unsignedBigInteger('news_id');
            $table->foreign('news_id')
                ->references('id')
                ->on('news')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bloger_news');
    }
}
