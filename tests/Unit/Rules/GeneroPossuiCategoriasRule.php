<?php
declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Rules\GeneroPossuiCategoriasRule;
use Illuminate\Contracts\Validation\Rule;
use Mockery\MockInterface;
use Tests\TestCase;

class GeneroPossuiCategoriasRuleTest extends TestCase
{
    public function testInstanceRules()
    {
        $rule = new GeneroPossuiCategoriasRule([]);
        $this->assertInstanceOf(Rule::class, $rule);
    }

    public function testCategoriasIdField()
    {
        $rule = new GeneroPossuiCategoriasRule(
            [1, 1, 2, 2]
        );
        $reflectionClass = new \ReflectionClass(GeneroPossuiCategoriasRule::class);
        $reflectionProperty = $reflectionClass->getProperty('categoriasId');
        $reflectionProperty->setAccessible(true);

        $categoriasId = $reflectionProperty->getValue($rule);
        $this->assertEqualsCanonicalizing([1, 2], $categoriasId);
    }

    public function testGenerosIdValue()
    {
        $rule =$this->createRuleMock([]);
        $rule->shouldReceive('getCategoriasPorGeneros')
            ->withAnyArgs()
            ->andReturnNull();

        $rule->passes('', [1, 1, 2, 2]);

        $reflectionClass = new \ReflectionClass(GeneroPossuiCategoriasRule::class);
        $reflectionProperty = $reflectionClass->getProperty('generosId');
        $reflectionProperty->setAccessible(true);

        $generosId = $reflectionProperty->getValue($rule);
        $this->assertEqualsCanonicalizing([1, 2], $generosId);
    }

    public function testRetornaFalseQuandoCategoriaOuGeneroEstaLimpo()
    {
        $rule = $this->createRuleMock([1]);
        $this->assertFalse($rule->passes('', []));

        $rule = $this->createRuleMock([]);
        $this->assertFalse($rule->passes('', [1]));
    }

    public function testRetornaFalseQuandoCategoriasPorGenerosEstaVazio()
    {
        $rule = $this->createRuleMock([1]);
        $rule->shouldReceive('getCategoriasPorGeneros')
            ->withAnyArgs()
            ->andReturn(collect([]));
        $this->assertFalse($rule->passes('', [1]));
    }

    public function testRetonraFlaseQuandoTemCategoriaSemGenero()
    {
        $rule = $this->createRuleMock([1, 2]);
        $rule->shouldReceive('getCategoriasPorGeneros')
            ->withAnyArgs()
            ->andReturn(collect(['categoria_id' => 1]));
        $this->assertFalse($rule->passes('', [1]));
    }

    public function testPassesEstaValido()
    {
        $rule = $this->createRuleMock([1, 2]);
        $rule->shouldReceive('getCategoriasPorGeneros')
            ->withAnyArgs()
            ->andReturn(collect([
                ['categoria_id' => 1],
                ['categoria_id' => 2],
            ]));
        $this->assertTrue($rule->passes('', [1]));
    }

    protected function createRuleMock(array $categoriasId): MockInterface
    {
        return \Mockery::mock(GeneroPossuiCategoriasRule::class, [$categoriasId])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }
}

