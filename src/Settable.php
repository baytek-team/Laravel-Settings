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
    public function registerSettings($settings)
    {
        foreach($settings as $name => $class) {

            $packageSettings = collect((new $class)->getSettings());
            $appSettings = collect(config($name));

            // This is where we need to check to see if the logged in user has any saved settings
            $userSettings = collect([]);

            $result = $packageSettings->merge($appSettings)->merge($userSettings);

            app('config')->set('content.'.$name, $result);

            // View::composer(ucfirst($name).'::*', function($view) use($result, $name)
            // {
            //     $view->with($name.'_settings', $result);
            // });
        }
    }
}