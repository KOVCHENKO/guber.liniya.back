<?php

use Faker\Generator as Faker;

$factory->define(\App\src\Models\Call::class, function (Faker $faker) {
    return [
        'call_id' => '59a8ce80-541d-4340-b123-3035bb9469ff',
        'phone' => '87252211782',
        'link' => 'https://records.megapbx.ru/record/pravastrobl.megapbx.ru/2018-10-01/59a8ce80-541d-4340-b123-3035bb9469ff/apostolov_in_2018_10_01%2D15_48_42_79270713090_linx.mp3',
        'ats_status' => 'Success',
        'type' => 'in',
        'processing_status' => 'raw',
    ];
});
