<?php

use Faker\Generator as Faker;

$factory->define(App\PointOfInterest::class, function (Faker $faker) {
	/*
	SPAIN
	lng (min/max): 39.806720569134/44.156419430866
	lat (min/max): -0.49253104347826/3.8552950434783
	CATALONIA
	lng (min/max): 39.806720569134/44.156419430866
	lat (min/max): -0.49253104347826/3.8552950434783
	BARCELONA
	lng (min/max): 39.204727603087/43.555490396913
	lat (min/max): -0.068634043478261/4.2791920434783
	*/
	$access_token = env('MAPBOX_API_KEY','');
	$latitude = $faker->latitude(41.409287,41.413857);
	$longitude = $faker->longitude(2.208547,2.223904);
	$address = '';
	$address__mapbox_id = '';
	$postal_code = '';
	$postal_code__mapbox_id = '';
	$region = '';
	$region__mapbox_id = '';
	$place = '';
	$place__mapbox_id = '';
	$country = '';
	$country__mapbox_id = '';

	$url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/'. $longitude . ',' . $latitude . '.json?access_token=' . $access_token;
    $centroid_info = json_decode(file_get_contents($url), true);

    foreach ($centroid_info['features'] as $key => $feature) {
        $aux = explode(".", $feature['id']);
        switch ($aux[0]) {
            case 'address':
                $address = $feature['place_name'];
                $address__mapbox_id = $aux[1];
                break;

            case 'postcode':
                $postal_code = $feature['text'];
                $postal_code__mapbox_id = $aux[1];
                break;

            case 'region':
                $region = $feature['text'];
                $region__mapbox_id = $aux[1];
                break;

            case 'place':
                $place = $feature['text'];
                $place__mapbox_id = $aux[1];
                break;

            case 'country':
                $country = $feature['text'];
                $country__mapbox_id = $aux[1];
                break;
        }
    }
    return [
    	'id_odor_type' => $faker->numberBetween($min = 1, $max = 62),
	    'id_admin' => '1',
	    'name' => $faker->sentence($nbWords = 3, $variableNbWords = true),
	    'slug' => $faker->slug($nbWords = 3),
	    'icon' => '',
	    'address' => $address,
	    'address__mapbox_id' => $address__mapbox_id,
		'postal_code' => $postal_code,
		'postal_code__mapbox_id' => $postal_code__mapbox_id,
		'place' => $place,
		'place__mapbox_id' => $place__mapbox_id,
		'region' => $region,
		'region__mapbox_id' => $region__mapbox_id,
		'country' => $country,
		'country__mapbox_id' => $country__mapbox_id,
		'latitude' => $latitude,
		'longitude' => $longitude,
		'published_at' => $faker->datetime(),
    ];
});