<?php
namespace Vp\VaultsPay;

use Illuminate\Support\ServiceProvider;

class VaultsPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge default config
        $this->mergeConfigFrom(__DIR__ . '/../config/vaultspay.php', 'vaultspay');

        // Bind manager as singleton
        $this->app->singleton('vaultspay', function ($app) {
            return new VaultsPayManager($app['config']->get('vaultspay'));
        });
    }

    public function boot()
    {
        /*
        |--------------------------------------------------------------------------
        | Publish config
        |--------------------------------------------------------------------------
        */
        $this->publishes([
            __DIR__ . '/../config/vaultspay.php' => config_path('vaultspay.php'),
        ], 'vaultspay-config');

        /*
        |--------------------------------------------------------------------------
        | Load package routes
        |--------------------------------------------------------------------------
        */
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');     // success + check
        $this->loadRoutesFrom(__DIR__ . '/routes/webhook.php'); // webhook

        /*
        |--------------------------------------------------------------------------
        | Load package views
        |--------------------------------------------------------------------------
        */
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'vaultspay'
        );
    }
}
