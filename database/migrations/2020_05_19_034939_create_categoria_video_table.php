<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriaVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoria_video', function (Blueprint $table) {
            $table->uuid('categoria_id')->index();
            $table->foreign('categoria_id')->references('id')->on('categorias');

            $table->uuid('video_id')->index();
            $table->foreign('video_id')->references('id')->on('videos');

            $table->unique(['categoria_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoria_video');
    }
}
