<?php

namespace Tests\Unit\Models;

use App\Models\Genero;
use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class GeneroTest extends TestCase
{
    /** @var Genero */
    private $genero ;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genero =  new Genero();
    }

    public function testFillableAttribute()
    {
        $fillable = ['nome', 'ativo'];
        $this->assertEquals($fillable, $this->genero->getFillable());
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

    public function testCastsAttribute()
    {
        $casts = ['id' => 'string'];
        $castsGenero = $this->genero->getCasts();
        $this->assertEquals($casts, $castsGenero);
    }

    public function testIncrementsAttribute()
    {
        $this->assertFalse($this->genero->incrementing);
    }

    public function testeDatesAttribute()
    {
        $datas = ['deleted_at', 'created_at', 'updated_at'];
        $dataGeneros = $this->genero->getDates();
        $this->assertEqualsCanonicalizing($datas,$dataGeneros);
        $this->assertCount(count($dataGeneros), $dataGeneros);
    }
}
