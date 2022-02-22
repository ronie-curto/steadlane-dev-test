<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Medication;
use Faker\Generator as Faker;

$factory->define(Medication::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
