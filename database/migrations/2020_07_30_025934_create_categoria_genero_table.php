<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriaGeneroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoria_genero', function (Blueprint $table) {
            $table->uuid('categoria_id')->index();
            $table->foreign('categoria_id')->references('id')->on('categorias');

            $table->uuid('genero_id')->index();
            $table->foreign('genero_id')->references('id')->on('generos');

            $table->unique(['categoria_id', 'genero_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoria_genero');
    }
}
