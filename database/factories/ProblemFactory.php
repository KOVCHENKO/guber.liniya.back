<?php

use Faker\Generator as Faker;

$factory->define(\App\src\Models\Problem::class, function (Faker $faker) {
    return [
        'name' => 'dummy_name',
        'description' => 'dummy_description',
        'problem_type_id' => 0
    ];
});
