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

    public function testInvalidacaoDados()
    {
        $dados = [
            'nome' => '',
            'tipo' => ''
        ];
        $this->assertInvalidationDataInStoreAction($dados,'required');
        $this->assertInValidationDataInUpdateAction($dados,'required');

        $dados = [
            'nome' => str_repeat('a', 256)
        ];
        $this->assertInvalidationDataInStoreAction($dados,'max.string', ['max' => 255]);
        $this->assertInvalidationDataInUpdateAction($dados,'max.string', ['max' => 255]);

        $dados = [
            'tipo' => 'abc'
        ];
        $this->assertInvalidationDataInStoreAction($dados,'numeric');
        $this->assertInvalidationDataInUpdateAction($dados,'numeric');

        $dados = $this->elenco->only('nome');
        $this->assertInvalidationDataInStoreAction($dados,'unique');

        // TODO: acrescentar valdiacao de unique update

         $dados = [
            'tipo' => 0
        ];
        $this->assertInvalidationDataInStoreAction($dados,'in');
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
