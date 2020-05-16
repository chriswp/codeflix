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
        $response->assertJsonMissingValidationErrors(['tipo']);

        $response = $this->json('POST', route('elencos.store'), [
            'nome' => str_repeat('a', 256),
            'tipo' => Elenco::DIRETOR
        ]);

        $this->assertValidationData($response, ['nome'], 'max.string', ['max' => 255]);
        $this->assertValidationData($response, ['tipo'], 'in', ['in' => [Elenco::DIRETOR, Elenco::ATOR]]);
    }

    public function testStore()
    {
        $dadosElenco = [
            'nome' => 'categoria teste 2',
            'ativo' => false
        ];

        $response = $this->assertStore($dadosElenco,
            array_merge($dadosElenco, ['ativo' => false, 'descricao' => 'descricao teste']));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testStoreSomenteNomeInformado()
    {
        $dadosElenco = [
            'nome' => 'categoria teste'
        ];
        $response = $this->assertStore($dadosElenco,
            array_merge($dadosElenco, ['ativo' => true, 'descricao' => null]));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testUpdate()
    {
        $dadosElenco = [
            'nome' => 'categoria update',
            'ativo' => false,
            'descricao' => 'test'
        ];

        $response = $this->assertUpdate($dadosElenco,
            array_merge($dadosElenco,
                ['deleted_at' => null, 'ativo' => false, 'nome' => 'categoria update', 'descricao' => 'test']));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testUpdateDescricaoNull()
    {
        $dadosElenco['descricao'] = '';
        $this->assertUpdate($dadosElenco, array_merge($dadosElenco, ['descricao' => null]));
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
