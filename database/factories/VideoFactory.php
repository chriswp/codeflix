<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Video;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {

    $classificacao = Video::CLASSIFICACOES[array_rand(Video::CLASSIFICACOES)];
    return [
        'titulo' => $faker->sentence(3),
        'descricao' => $faker->sentence(10),
        'ano_lancamento' => rand(1895,2020),
        'liberado' => rand(0,1),
        'classificacao' => $classificacao,
        'duracao' => rand(1,30)
    ];
});
