<?php

namespace Partymeister\Accounting\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;
use Partymeister\Accounting\Models\Sale;

/**
 * Class PartymeisterServiceProvider
 */
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
        $this->publishResourceAssets();
        $this->components();
        merge_local_config_with_db_configuration_variables('partymeister-accounting');
    }

    public function config() {}

    public function routes()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../../routes/web.php';
            require __DIR__.'/../../routes/api.php';
        }
    }

    public function routeModelBindings()
    {
        Route::bind('account_type', function ($id) {
            return AccountType::findOrFail($id);
        });

        Route::bind('account', function ($id) {
            return Account::findOrFail($id);
        });

        Route::bind('booking', function ($id) {
            return Booking::findOrFail($id);
        });

        Route::bind('item_type', function ($id) {
            return ItemType::findOrFail($id);
        });

        Route::bind('item', function ($id) {
            return Item::findOrFail($id);
        });

        Route::bind('sale', function ($id) {
            return Sale::findOrFail($id);
        });
    }

    public function translations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'partymeister-accounting');

        $this->publishes([
            __DIR__.'/../../resources/lang' => resource_path('lang/vendor/partymeister-accounting'),
        ], 'motor-backend-translations');
    }

    public function views()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'partymeister-accounting');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/partymeister-accounting'),
        ], 'motor-backend-views');
    }

    public function navigationItems()
    {
        $config = $this->app['config']->get('motor-backend-navigation', []);
        $this->app['config']->set('motor-backend-navigation', array_replace_recursive(require __DIR__.'/../../config/motor-backend-navigation.php', $config));
    }

    public function permissions()
    {
        $config = $this->app['config']->get('motor-backend-permissions', []);
        $this->app['config']->set('motor-backend-permissions', array_replace_recursive(require __DIR__.'/../../config/motor-backend-permissions.php', $config));
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
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    public function validators()
    {
        Validator::extend('currency_compatibility', 'Partymeister\Accounting\Validators\CurrencyCompatibilityValidator@validate');
    }

    public function publishResourceAssets()
    {
        $assets = [
            __DIR__.'/../../resources/assets/sass' => resource_path('assets/sass'),
            __DIR__.'/../../resources/assets/js' => resource_path('assets/js'),
            __DIR__.'/../../resources/assets/npm' => resource_path('assets/npm'),
        ];

        $this->publishes($assets, 'partymeister-accounting-install-resources');
    }

    public function components()
    {
        $config = $this->app['config']->get('motor-cms-page-components', []);
        $this->app['config']->set('motor-cms-page-components', array_replace_recursive(require __DIR__.'/../../config/motor-cms-page-components.php', $config));
    }
}
