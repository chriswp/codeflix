<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Elenco;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\SaveDataTest;
use Tests\Traits\ValidationsTest;

class ElencoControllerTest extends TestCase
{
    use DatabaseMigrations, ValidationsTest, SaveDataTest;

    /** @var Elenco */
    private $elenco;

    protected function setUp(): void
    {
        parent::setUp();
        $this->elenco = factory(Elenco::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('elencos.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$this->elenco->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('elencos.show', ['elenco' => $this->elenco->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->elenco->toArray());
    }

    public function testStoreValidation()
    {
        $response = $this->json('POST', route('elencos.store'), []);
        $this->assertValidationData($response, ['nome'], 'required');
        $this->assertValidationData($response, ['tipo'], 'required');

        $response = $this->json('POST', route('elencos.store'), [
            'nome' => str_repeat('a', 256),
            'tipo' => 'abc'
        ]);

        $this->assertValidationData($response, ['nome'], 'max.string', ['max' => 255]);
        $this->assertValidationData($response, ['tipo'], 'numeric');

        $dadosElenco = $this->elenco->only('nome','tipo');
        $response = $this->json('POST', route('elencos.store'), $dadosElenco);
        $this->assertValidationData($response, ['nome'], 'unique');

        $dadosElenco['tipo'] = 0;
        $response = $this->json('POST', route('elencos.store'), $dadosElenco);
        $this->assertValidationData($response, ['tipo'], 'in');
    }

    public function testStore()
    {
        $dadosElenco = [
            'nome' => 'elenco teste 2',
            'tipo' => Elenco::DIRETOR
        ];

        $response = $this->assertStore($dadosElenco,$dadosElenco);
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }


    public function testUpdate()
    {
        $dadosElenco = [
            'nome' => 'elenco update 2',
            'tipo' => Elenco::ATOR
        ];

        $response = $this->assertUpdate($dadosElenco,
            array_merge($dadosElenco,['deleted_at' => null, 'nome' => 'elenco update 2', 'tipo' => Elenco::ATOR]));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testDelete()
    {
        $elenco = factory(Elenco::class)->create();
        $response = $this->json('DELETE', route('elencos.destroy', ['elenco' => "{$elenco->id}"]));
        $response->assertStatus(204);
        $this->assertNull(Elenco::find($elenco->id));
        $this->assertNotNull(Elenco::withTrashed()->find($elenco->id));
    }

    protected function model()
    {
        return Elenco::class;
    }

    protected function routeStore()
    {
        return route('elencos.store');
    }

    protected function routeUpdate()
    {
        return route('elencos.update', ['elenco' => $this->elenco->id]);
    }
}
