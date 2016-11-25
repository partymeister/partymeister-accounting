<?php

namespace Partymeister\Accounting\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class PartymeisterServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->config();
        $this->routes();
        $this->routeModelBindings();
        $this->translations();
        $this->views();
        $this->navigationItems();
        $this->permissions();
        $this->registerCommands();
        $this->migrations();
        $this->validators();

    }

    public function validators()
    {
        Validator::extend('currency_compatibility', 'Partymeister\Accounting\Validators\CurrencyCompatibilityValidator@validate');
    }


    public function config()
    {

    }


    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([// add commands here
            ]);
        }
    }


    public function migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }


    public function permissions()
    {
        $config = $this->app['config']->get('motor-backend-permissions', []);
        $this->app['config']->set('motor-backend-permissions',
            array_replace_recursive(require __DIR__ . '/../../config/motor-backend-permissions.php', $config));
    }


    public function routes()
    {
        if ( ! $this->app->routesAreCached()) {
            require __DIR__ . '/../../routes/web.php';
            require __DIR__ . '/../../routes/api.php';
        }
    }


    public function translations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'partymeister-accounting');

        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/partymeister-accounting'),
        ], 'motor-backend-translations');
    }


    public function views()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'partymeister-accounting');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/partymeister-accounting'),
        ], 'motor-backend-views');
    }


    public function routeModelBindings()
    {
        Route::bind('account_type', function ($id) {
            return \Partymeister\Accounting\Models\AccountType::findOrFail($id);
        });

        Route::bind('account', function ($id) {
            return \Partymeister\Accounting\Models\Account::findOrFail($id);
        });

        Route::bind('booking', function($id){
            return \Partymeister\Accounting\Models\Booking::findOrFail($id);
        });

        Route::bind('item_type', function($id){
            return \Partymeister\Accounting\Models\ItemType::findOrFail($id);
        });
    }


    public function navigationItems()
    {
        $config = $this->app['config']->get('motor-backend-navigation', []);
        $this->app['config']->set('motor-backend-navigation',
            array_replace_recursive(require __DIR__ . '/../../config/motor-backend-navigation.php', $config));
    }
}
