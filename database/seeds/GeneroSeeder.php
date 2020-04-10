<?php

use Illuminate\Database\Seeder;

class GeneroSeeder extends Seeder
{
    public function run()
    {
       factory(\App\Models\Genero::class,10)->create();
    }
}
