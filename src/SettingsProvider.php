<?php

namespace Baytek\Laravel\Settings;

use Baytek\Laravel\Settings\Setting;
use Baytek\Laravel\Settings\Models\Settings as SettingModel;

class SettingsProvider
{
    public $providers = [];

    public function __construct()
    {
        if(config('settings.providers')) {
            foreach (config('settings.providers') as $name => $provider) {
                $this->providers[$name] = $provider;
            }
        }

        $this->resolve();
    }

    /**
     * Register the settings classes
     *
     * @return void
     */
    public function resolve()
    {
        foreach ($this->providers as $name => $class) {
            // $class = $this->providers[$name];
            $appSettings = config($name);

            $contentTypeSettings = new $class;

            $contentSettings = collect($contentTypeSettings->getSettings());
            $contentSettings = $this->processSettings($contentSettings);

            $cmsSettings = collect([]);

            // Get the keys that will be searched
            $keys = collect($contentTypeSettings->getPublicKeys())->map(function ($item) use ($name) {
                return "$name.$item";
            })->all();

            // With the bunch of results
            SettingModel::whereIn('key', $keys)->get()->each(function ($setting) use ($name, &$cmsSettings) {
                $value = $setting->value;

                if (class_exists($setting->type)) {
                    $value = (new $setting->type($setting->value))->unpack();
                }

                $cmsSettings->put(substr($setting->key, strlen($name) + 1), $value);
            });

            // This is where we need to check to see if the logged in user has any saved settings
            $userSettings = collect([]);

            // Merge all of the possible settings together
            $result = $contentSettings
                ->merge($appSettings)
                ->merge($cmsSettings)
                ->merge($userSettings)->all();

            // Get the settings namespace in settings config
            $settingsNamespace = collect([
                config('settings.namespace'),
                $name
            ])->filter()->implode('.');

            // Write the settings to the main config
            app('config')->set($settingsNamespace, $result);
        }
    }

    protected function processSettings(&$settings)
    {
        foreach ($settings as $key => $setting) {
            $settings[$key] = $this->processSetting($setting);
        }

        return $settings;
    }

    protected function processSetting($setting)
    {
        if (is_subclass_of($setting, Setting::class)) {
            $setting->validate();
            return $setting->value;
        }

        return $setting;
    }
}
