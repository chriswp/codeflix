<?php

namespace Tests\Feature\Rules;

use App\Models\Categoria;
use App\Models\Genero;
use App\Rules\GeneroPossuiCategoriasRule;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GeneroPossuiCategoriasRuleTest extends TestCase
{
    use DatabaseMigrations;

    /** @var Collection */
    private $categorias;
    private $generos;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categorias = factory(Categoria::class, 4)->create();
        $this->generos = factory(Genero::class, 2)->create();

        $this->generos[0]->categorias()->sync([
            $this->categorias[0]->id,
            $this->categorias[1]->id
        ]);
        $this->generos[1]->categorias()->sync([
            $this->categorias[2]->id
        ]);
    }

    public function testPassesIsValid()
    {
        $rule = new GeneroPossuiCategoriasRule([
            $this->categorias[2]->id
        ]);
        $isValid = $rule->passes('', [
            $this->generos[1]->id
        ]);
        $this->assertTrue($isValid);

        $rule = new GeneroPossuiCategoriasRule([
            $this->categorias[0]->id,
            $this->categorias[2]->id
        ]);
        $isValid = $rule->passes('', [
            $this->generos[0]->id,
            $this->generos[1]->id,
        ]);
        $this->assertTrue($isValid);


        $rule = new GeneroPossuiCategoriasRule([
            $this->categorias[0]->id,
            $this->categorias[1]->id,
            $this->categorias[2]->id
        ]);
        $isValid = $rule->passes('', [
            $this->generos[0]->id,
            $this->generos[1]->id
        ]);
        $this->assertTrue($isValid);
    }

    public function testPassesIsNotValid()
    {
        $rule = new GeneroPossuiCategoriasRule([
            $this->categorias[0]->id
        ]);
        $isValid = $rule->passes('', [
            $this->generos[0]->id,
            $this->generos[1]->id
        ]);
        $this->assertFalse($isValid);

        $rule = new GeneroPossuiCategoriasRule([
            $this->categorias[3]->id
        ]);
        $isValid = $rule->passes('', [
            $this->generos[0]->id
        ]);
        $this->assertFalse($isValid);
    }
}
