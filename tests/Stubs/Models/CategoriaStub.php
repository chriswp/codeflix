<?php

namespace Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategoriaStub extends Model
{
    protected $table = 'categoria_stubs';
    protected $fillable = ['nome','descricao'];


    public static function createTable(){
        Schema::create('categoria_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->timestamps();
        });
    }

    public static function dropTable()
    {
        Schema::dropIfExists('categoria_stubs');
    }

}
