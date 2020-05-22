<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\SaveDataTest;
use Tests\Traits\ValidationsTest;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations, SaveDataTest, ValidationsTest;

    /** @var Video */
    private $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = factory(Video::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('videos.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$this->video->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('videos.show', ['video' => $this->video->id]));
        $response
            ->assertStatus(200)
            ->assertJson($this->video->toArray());
    }

    public function testSave()
    {
        $dadosVideo = [];
        $response = $this->assertStore($dadosVideo,
            array_merge($dadosVideo, ['ativo' => false, 'nome' => 'video 1']));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testInvalidationRequired()
    {
        $dados = [
            'titulo' => '',
            'descricao' => '',
            'ano_lancamento' => '',
            'liberado' => '',
            'classificacao' => '',
            'duracao' => '',
            'categorias_id' => '',
            'generos_id' => '',
        ];

        $this->assertValidationData('');
   }


    public function testDelete()
    {
        $video = factory(video::class, 1)->create()->first();
        $response = $this->json('DELETE', route('videos.destroy', ['video' => "{$this->video->id}"]));
        $response->assertStatus(204);
        $this->assertNull(video::find($this->video->id));
        $this->assertNotNull(video::withTrashed()->find($video->id));
    }

    protected function model()
    {
        return video::class;
    }

    protected function routeStore()
    {
        return route('videos.store');
    }

    protected function routeUpdate()
    {
        return route('videos.update', ['video' => "{$this->video->id}"]);
    }
}
