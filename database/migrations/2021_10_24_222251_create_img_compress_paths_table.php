<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImgCompressPathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('img_compress_paths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url_compress');
            $table->string('img_name');

            $table->integer('profile_id')->unsigned();


            $table->foreign('profile_id')
                ->references('id')->on('profiles')
                ->onDelete('cascade');

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
        Schema::dropIfExists('img_compress_paths');
    }
}
