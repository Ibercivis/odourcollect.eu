<?php

namespace App\Providers;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

class FakerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Faker', function($app) {
            $faker = Factory::create('es_ES');
            $newClass = new class($faker) extends \Faker\Provider\Base {
                public function slug($title, $nbWords = 4)
                {
                    $sentence = $this->generator->sentence($nbWords);
                    return substr($sentence, 0, strlen($sentence) - 1);
                }
            };

            $faker->addProvider($newClass);
            return $faker;
        });
    }
}
