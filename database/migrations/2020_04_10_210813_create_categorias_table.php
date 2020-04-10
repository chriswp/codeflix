<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasTable extends Migration
{
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('categorias');
    }
}
