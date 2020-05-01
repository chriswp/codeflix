<?php

namespace Tests\Unit;

use App\Models\Categoria;
use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CategoriaTest extends TestCase
{
    public function testFillableAttribute()
    {
        $fillable = ['nome', 'descricao', 'ativo'];
        $categoria = new Categoria();
        $this->assertEquals($fillable, $categoria->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            GenerateUuid::class
        ];
        $traitsCategoria = array_keys(class_uses(Categoria::class));
        $this->assertEquals($traits, $traitsCategoria);
    }

    public function testCatsAttribute()
    {
        $casts = ['id' => 'string'];
        $categoria = new Categoria();
        $castsCategoria = $categoria->getCasts();
        $this->assertEquals($casts, $castsCategoria);
    }

    public function testIncrementsAttribute()
    {
        $categoria = new Categoria();
        $this->assertFalse($categoria->incrementing);
    }

    public function testeDatesAttribute()
    {
        $datas = ['deleted_at', 'created_at', 'updated_at'];
        $categoria = new Categoria();
        $dataCategorias = $categoria->getDates();
        foreach ($datas as $data) {
            $this->assertContains($data, $dataCategorias);
        }
        $this->assertCount(count($dataCategorias), $dataCategorias);
    }
}
