<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use App\Marvel\Marvel as Marvel;

class MarvelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind('App\Marvel\Contracts\MarvelContract', function(){
        $config = [
          'public' => env('MARVEL_PUBLIC_KEY'),
          'private' => env('MARVEL_PRIVATE_KEY'),
          'version' => env("MARVEL_API_VERSION"),
          'base' => env("MARVEL_API_URI"),
        ];

        return new Marvel($config, new Client());
      });
    }
}
