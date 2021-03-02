<?php

use Faker\Generator as Faker;

$factory->define(App\Odor::class, function (Faker $faker) {
    return [
    	'id_odor_type' => $faker->numberBetween($min = 1, $max = 62),
	    'id_user' => $faker->numberBetween($min = 1, $max = 15),
	    'name' => $faker->sentence($nbWords = 3, $variableNbWords = true),
	    'slug' => $faker->slug($nbWords = 3),
	    'description' => $faker->sentence($nbWords = 10, $variableNbWords = true),
	    'id_odor_intensity' => $faker->numberBetween($min = 1, $max = 7),
	    'id_odor_duration' => $faker->numberBetween($min = 1, $max = 3),
	    'id_odor_annoy' => $faker->numberBetween($min = 1, $max = 9),
	    'published_at' => $faker->datetime(),
    ];
});