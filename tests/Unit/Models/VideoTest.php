<?php

namespace Tests\Unit\Models;

use App\Models\Traits\UploadFiles;
use App\Models\Video;
use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    /** @var Video */
    private $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = new Video();
    }

    public function testFillableAttribute()
    {
        $fillable = ['titulo', 'descricao', 'ano_lancamento', 'liberado', 'classificacao', 'duracao','video_arquivo'];
        $this->assertEquals($fillable, $this->video->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            GenerateUuid::class,
            UploadFiles::class
        ];
        $traitsVideo = array_keys(class_uses(Video::class));
        $this->assertEquals($traits, $traitsVideo);
    }

    public function testCastsAttribute()
    {
        $casts = [
            'id' => 'string',
            'ano_lancamento' => 'integer',
            'liberado' => 'boolean',
            'duracao' => 'integer'
        ];
        $castsVideo = $this->video->getCasts();
        $this->assertEquals($casts, $castsVideo);
    }

    public function testIncrementsAttribute()
    {
        $this->assertFalse($this->video->incrementing);
    }

    public function testeDatesAttribute()
    {
        $datas = ['deleted_at', 'created_at', 'updated_at'];
        $dataVideos = $this->video->getDates();
        $this->assertEqualsCanonicalizing($datas, $dataVideos);
        $this->assertCount(count($dataVideos), $dataVideos);
    }
}
