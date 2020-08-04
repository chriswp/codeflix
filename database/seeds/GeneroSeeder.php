<?php

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Genero;

class GeneroSeeder extends Seeder
{
    public function run()
    {
        $categorias = Categoria::all();
        factory(Genero::class, 10)
            ->create()
            ->each(function (Genero $genero) use ($categorias) {
                $categoriasId = $categorias->random(5)->pluck('id')->toArray();
                $genero->categorias()->sync($categoriasId);
            });
    }
}
