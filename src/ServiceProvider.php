<?php

namespace Baytek\Laravel\Settings;

use Illuminate\Support\ServiceProvider;

use View;

class SettingsServiceProvider extends ServiceProvider
{

    protected $settings = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach($this->settings as $name => $settings) {
            $packageSettings = collect($settings->getSettings());
            $appSettings = collect(config($name));

            // This is where we need to check to see if the logged in user has any saved settings
            $userSettings = collect([]);

            $settings = $packageSettings->merge($appSettings)->merge($userSettings);

            View::composer(ucfirst($name).'::*', function($view)
            {
                $view->with($name.'.settings', $settings);
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function registerSettings($settings)
    {
        $this->settings = collect($this->settings)->merge($settings)->all();
    }

}