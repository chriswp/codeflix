<?php

namespace Tests\Unit;

use App\Models\Genero;
use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class GeneroTest extends TestCase
{
    public function testFillableAttribute()
    {
        $fillable = ['nome', 'ativo'];
        $categoria = new Genero();
        $this->assertEquals($fillable, $categoria->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            GenerateUuid::class
        ];
        $traitsGenero = array_keys(class_uses(Genero::class));
        $this->assertEquals($traits, $traitsGenero);
    }

    public function testCatsAttribute()
    {
        $casts = ['id' => 'string'];
        $categoria = new Genero();
        $castsGenero = $categoria->getCasts();
        $this->assertEquals($casts, $castsGenero);
    }

    public function testIncrementsAttribute()
    {
        $categoria = new Genero();
        $this->assertFalse($categoria->incrementing);
    }

    public function testeDatesAttribute()
    {
        $datas = ['deleted_at', 'created_at', 'updated_at'];
        $categoria = new Genero();
        $dataGeneros = $categoria->getDates();
        foreach ($datas as $data) {
            $this->assertContains($data, $dataGeneros);
        }
        $this->assertCount(count($dataGeneros), $dataGeneros);
    }
}
