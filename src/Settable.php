<?php

namespace Baytek\Laravel\Settings;

use Baytek\Laravel\Settings\Setting;
use Baytek\Laravel\Settings\Models\Settings as SettingModel;

use Settings;

trait Settable
{
    protected function processSettings(&$settings)
    {
        foreach($settings as $key => $setting) {
            $settings[$key] = $this->processSetting($setting);
        }

        return $settings;
    }

    protected function processSetting($setting)
    {
        if(is_subclass_of($setting, Setting::class)) {
            $setting->validate();
            return $setting->value();
        }
        return $setting;
    }

    /**
     * Register the settings classes
     *
     * @return void
     */
    public function registerSettings($settings)
    {
        $provider = new \Baytek\Laravel\Settings\SettingsProvider();

        foreach($settings as $name => $class) {

            $provider->register($name, $class);

            $appSettings = config($name);

            $contentTypeSettings = new $class;

            $contentSettings = collect($contentTypeSettings->getSettings());
            $contentSettings = $this->processSettings($contentSettings);

            $cmsSettings = collect([]);

            // Get the keys that will be searched
            $keys = collect($contentTypeSettings->getPublicKeys())->map(function($item) use ($name)
            {
                return "$name.$item";
            })->all();

            // With the bunch of results
            SettingModel::whereIn('key', $keys)->get()->each(function($setting) use ($name, &$cmsSettings)
            {
                $value = $setting->value;
                if(is_subclass_of($setting, Setting::class)) {
                    $value = (new $setting->type($setting->value))->unpack();
                }
                $cmsSettings->put(substr($setting->key, strlen($name) + 1), $value);
            });

            // This is where we need to check to see if the logged in user has any saved settings
            $userSettings = collect([]);


            $result = $contentSettings
                ->merge($appSettings)
                ->merge($cmsSettings)
                ->merge($userSettings)->all();

            app('config')->set('cms.content.'.$name, $result);
        }
    }
}