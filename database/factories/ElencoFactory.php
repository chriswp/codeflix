<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Elenco;
use Faker\Generator as Faker;

$factory->define(Elenco::class, function (Faker $faker) {
    return [
        'nome' => $faker->lastName,
        'tipo' => $faker->numberBetween(1,2)
    ];
});
