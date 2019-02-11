<?php

use App\src\Models\ProblemType;
use Faker\Generator as Faker;

$factory->define(ProblemType::class, function (Faker $faker) {
    return [
        'name' => 'dummy_name',
        'description' => 'dummy_description'
    ];
});
