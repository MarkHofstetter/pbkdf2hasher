<?php

namespace MarkHofstetter\Pbkdf2Hasher;
# namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Support\Facades\Hash;
use Pbkdf2Hasher;


# class MarkHofstetter_Pbkdf2Hasher_Pbkdf2HasherServiceProvider extends ServiceProvider
class Pbkdf2HasherServiceProvider extends ServiceProvider
# class HashServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('hash')->extend('pbkdf2', function () {
            return new Pbkdf2Hasher($options = $this->app->config->get('pbkdf2hasher'));
        });

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // print_r($this->app->config->get('hashing.driver'));
        $this->mergeConfigFrom(__DIR__.'/../config/pbkdf2hasher.php', 'pbkdf2hasher');

        // Register the service the package provides.
        if ($this->app->config->get('hashing.driver') == 'pbkdf2') {
            $this->app->singleton('hash', function ($app) {
                return new Pbkdf2Hasher($options = $this->app->config->get('pbkdf2hasher'));
            });
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['pbkdf2hasher'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/pbkdf2hasher.php' => config_path('pbkdf2hasher.php'),
        ], 'pbkdf2hasher.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/markhofstetter'),
        ], 'pbkdf2hasher.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/markhofstetter'),
        ], 'pbkdf2hasher.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/markhofstetter'),
        ], 'pbkdf2hasher.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
