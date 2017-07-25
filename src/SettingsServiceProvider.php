<?php

namespace Baytek\Laravel\Settings;

use Baytek\Laravel\Settings\Models\Settings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class SettingsServiceProvider extends ServiceProvider
{
    protected $settings = [];

    protected $defer = true;

    /**
     * List of policies to be registered to the AuthServiceProvider
     * @var array
     */
    protected $policies = [
        Settings::class => SettingsPolicy::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'settings');
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/settings')
        ], 'migrations');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations')
        ], 'migrations');

        Route::group([
                'namespace' => \Baytek\Laravel\Settings::class,
                'middleware' => ['web'],
            ], function ($router)
            {
                // Add the default route to the routes list for this provider
                $router->get('admin/settings', 'SettingsController@settings')->name('settings.index');
                $router->post('admin/settings', 'SettingsController@save')->name('settings.save');
            });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SettingsProvider::class, function ($app) {
            return new SettingsProvider(config('cms'));
        });

        $this->app->register(RouteServiceProvider::class);
    }

    public function provides()
    {
        return [
            SettingsProvider::class
        ];
    }

}
