<?php

namespace Dbws\Braintree;

use Illuminate\Support\ServiceProvider;
use Inacho\CreditCard;


class BraintreeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'braintree');

        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishElements();
        $this->configBraintree();
        $this->app->singleton('braintree', function($app)
        {
            return new Braintree();
        });
    }
    private function configBraintree(){
        \Braintree_Configuration::environment(config('braintree.environment'));
        \Braintree_Configuration::merchantId(config('braintree.merchant_id'));
        \Braintree_Configuration::publicKey(config('braintree.public_key'));
        \Braintree_Configuration::privateKey(config('braintree.private_key'));
    }
    private function publishElements()
    {
        $configPath = __DIR__ . '/config/braintree.php';
        $this->publishes([$configPath => config_path('braintree.php')]);


        $this->publishes([
            __DIR__.'/views' => $this->app->resourcePath('views/vendor/braintree'),
        ], 'dbws-braintree');

       
        $routePath = __DIR__ . "/routes.php";
        $this->publishes([$routePath => base_path("app/Http/Controllers")], 'controllers');


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {   
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $loader->alias('braintree', 'Dbws\Braintree\Facades\Braintree');

        $this->app->make('Dbws\Braintree\Controllers\BraintreeController');
    }
}
