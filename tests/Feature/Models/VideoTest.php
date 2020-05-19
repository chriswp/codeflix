<?php

namespace Tests\Feature\Models;

use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreate()
    {
        $dados = [

        ];
        $video = Video::create($dados);
        $this->assertEquals(1,$video->count());
        $this->assertInstanceOf(Video::class,$video);
        $this->assertDatabaseHas($video->getTable(),$dados);
    }

    public function testIndex()
    {
        $videos = factory(Video::class,5)->create();;
        $this->assertEquals(5,$videos->count());
        $this->assertInstanceOf(Collection::class,$videos);
    }

    public function testUpdate()
    {
        $dados = [

        ];
        $video = factory(Video::class)->create();
        $video->update($dados);
        $this->assertInstanceOf(Video::class,$video);
        $this->assertDatabaseHas($video->getTable(),$dados);
    }

    public function testDelete()
    {
        $video = factory(Video::class)->create();
        $remover = $video->delete();
        $this->assertTrue($remover);
    }

    public function testValidateUuid()
    {
        $dados = [

        ];
        $video = Video::create($dados);
        $uuid = $video->id;
        $uuidValido = Uuid::isValid($uuid);
        $this->assertTrue($uuidValido);
    }
}
