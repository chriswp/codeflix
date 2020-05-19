<?php

namespace Tests\Unit\Models;

use App\Models\Elenco;
use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class ElencoTest extends TestCase
{
    /** @var Elenco */
    private $elenco ;

    protected function setUp(): void
    {
        parent::setUp();
        $this->elenco =  new Elenco();
    }

    public function testFillableAttribute()
    {
        $fillable = ['nome', 'tipo'];
        $this->assertEquals($fillable, $this->elenco->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            GenerateUuid::class
        ];
        $traitsElenco = array_keys(class_uses(Elenco::class));
        $this->assertEquals($traits, $traitsElenco);
    }

    public function testCastsAttribute()
    {
        $casts = ['id' => 'string','tipo' => 'integer'];
        $castsElenco = $this->elenco->getCasts();
        $this->assertEquals($casts, $castsElenco);
    }

    public function testIncrementsAttribute()
    {
        $this->assertFalse($this->elenco->incrementing);
    }

    public function testeDatesAttribute()
    {
        $datas = ['deleted_at', 'created_at', 'updated_at'];
        $dataElencos = $this->elenco->getDates();
        $this->assertEqualsCanonicalizing($datas,$dataElencos);
        $this->assertCount(count($dataElencos), $dataElencos);
    }
}
