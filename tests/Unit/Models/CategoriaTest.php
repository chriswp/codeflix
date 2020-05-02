<?php

namespace Tests\Unit\Models;

use App\Models\Categoria;
use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CategoriaTest extends TestCase
{
    /** @var Categoria */
    private $categoria ;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoria =  new Categoria();
    }

    public function testFillableAttribute()
    {
        $fillable = ['nome', 'descricao', 'ativo'];
        $this->assertEquals($fillable, $this->categoria->getFillable());
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

    public function testCastsAttribute()
    {
        $casts = ['id' => 'string'];
        $castsCategoria = $this->categoria->getCasts();
        $this->assertEquals($casts, $castsCategoria);
    }

    public function testIncrementsAttribute()
    {
        $this->assertFalse($this->categoria->incrementing);
    }

    public function testeDatesAttribute()
    {
        $datas = ['deleted_at', 'created_at', 'updated_at'];
        $dataCategorias = $this->categoria->getDates();
       $this->assertEqualsCanonicalizing($datas,$dataCategorias);
        $this->assertCount(count($dataCategorias), $dataCategorias);
    }
}
