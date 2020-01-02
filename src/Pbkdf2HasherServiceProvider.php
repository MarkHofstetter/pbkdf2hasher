<?php

namespace MarkHofstetter\Pbkdf2Hasher;

use Illuminate\Hashing\HashServiceProvider;
use Pbkdf2Hasher;

class Pbkdf2HasherServiceProvider extends HashServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

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

    }
}
