<?php

namespace Evryn\LaravelToman;

use Evryn\LaravelToman\Interfaces\TomanInterface;
use Evryn\LaravelToman\Managers\TomanManager;
use Illuminate\Support\ServiceProvider;

class LaravelTomanServiceProvider extends ServiceProvider
{
    const CONFIG_FILE = __DIR__.'/../config/toman.php';
    const TRANSLATION_FILES = __DIR__.'/../resources/lang/';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        $this->loadTranslationsFrom(self::TRANSLATION_FILES, 'toman');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_FILE, 'toman');

        $this->app->singleton(TomanInterface::class, function ($app) {
            return new TomanManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @codeCoverageIgnore
     * @return array
     */
    public function provides()
    {
        return [
            'laravel-toman.payment',
        ];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Make config and translation files publishable via artisan command

        $this->publishes([
            self::CONFIG_FILE => config_path('toman.php'),
        ], 'config');

        $this->publishes([
            self::TRANSLATION_FILES => resource_path('lang/vendor/toman'),
        ], 'lang');
    }
}
