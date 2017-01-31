<?php

namespace Baytek\Laravel\Settings;

use View;

trait Settable
{
    /**
     * Register the settings classes
     *
     * @return void
     */
    public function registerSettings($pairs)
    {
        foreach($pairs as $name => $settings) {
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
}