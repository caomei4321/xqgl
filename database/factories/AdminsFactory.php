<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Admin::class, function (Faker $faker) {
    return [
        'name' => 'cm',
        'phone' => '15555555555',
        'password' => bcrypt('admin'),
        'remember_token' => str_random(10),
    ];
});
