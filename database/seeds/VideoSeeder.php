<?php

use Illuminate\Database\Seeder;
use App\Models\Video;
use App\Models\Genero;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $generos = Genero::all();
        factory(Video::class, 100)
            ->create()
            ->each(function (Video $video) use ($generos) {
                $subGeneros = $generos->random(5)->load('categorias');
                $categoriasId = [];
                foreach ($subGeneros as $genero) {
                    array_push($categoriasId, ...$genero->categorias->pluck('id')->toArray());
                }
                $categoriasId = array_unique($categoriasId);
                $video->categorias()->attach($categoriasId);
                $video->generos()->attach($subGeneros->pluck('id')->toArray());
            });
    }
}
