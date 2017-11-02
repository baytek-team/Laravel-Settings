<?php

namespace Baytek\Laravel\Settings;

use Baytek\Laravel\Settings\Setting;
use Baytek\Laravel\Settings\Models\Settings as SettingModel;

class SettingsProvider
{
    /**
     * List of providers registered
     *
     * @var array
     */
    public $providers = [];

    /**
     * List of settings that have been resolved
     *
     * @var array
     */
    public $settings = [];

    /**
     * Constructor
     */
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
    protected function resolve()
    {
        // Loop through all of the providers
        foreach ($this->providers as $name => $class) {

            // Merge all of the possible settings together
            $this->settings[$name] =
                $this->resolveContentSettings($name, $class)
                ->merge($this->resolveAppSettings($name, $class))
                ->merge($this->resolveDatabaseSettings($name, $class))
                ->merge($this->resolveUserSettings($name, $class))
                ->all();

            // Write the settings to the main config
            $this->publishSettings(
                $this->getSettingsNamespace($name),
                $this->settings[$name]
            );
        }
    }

    /**
     * List the settings which currently exist for the system
     *
     * @param  string $name                    Name of the setting group
     * @param  string $class                   Class name setup for settings
     * @return Illuminate\Support\Collection   List of settings
     */
    protected function resolveAppSettings($name, $class)
    {
        return config($name);
    }

    /**
     * Get the default values for the given setting providers
     *
     * @param  string $name                    Name of the setting group
     * @param  string $class                   Class name setup for settings
     * @return Illuminate\Support\Collection   List of settings
     */
    protected function resolveContentSettings($name, $class)
    {
        $settings = (new $class)->getSettings();
        return collect(
            $this->processSettings(
                $settings
            )
        );
    }

    /**
     * Resolve the CMS saved database settings
     *
     * @param  string $name                    Name of the setting group
     * @param  string $class                   Class name setup for settings
     * @return Illuminate\Support\Collection   List of settings
     */
    protected function resolveDatabaseSettings($name, $class)
    {
        $cmsSettings = collect([]);

        // Get the keys that will be searched in the database
        $keys = collect((new $class)->getPublicKeys())->map(function ($item) use ($name) {
            return "$name.$item";
        })->all();

        // With a collection of keys search the database for existing values
        SettingModel::whereIn('key', $keys)->get()->each(function ($setting) use ($name, &$cmsSettings) {
            $value = $setting->value;

            if (class_exists($setting->type)) {
                $value = (new $setting->type($setting->value))->unpack();
            }

            $cmsSettings->put(substr($setting->key, strlen($name) + 1), $value);
        });

        return $cmsSettings;
    }

    /**
     * Resolve the users saved database settings
     *
     * @param  string $name                    Name of the setting group
     * @param  string $class                   Class name setup for settings
     * @return Illuminate\Support\Collection   List of settings
     */
    protected function resolveUserSettings($name, $class)
    {
        // This feature doesn't exist yet
        return collect([]);
    }

    /**
     * Get the namespace for the settings key
     *
     * @param  string $name   Name of the setting
     * @return string         Dot notation namespace
     */
    protected function getSettingsNamespace($name)
    {
        return collect([
            config('settings.namespace'),
            $name
        ])->filter()->implode('.');
    }

    /**
     * Publish the settings to the app settings
     * @param  string $key      The settings namespace
     * @param  Array  $settings List of settings to publish
     * @return void
     */
    protected function publishSettings($key, Array $settings)
    {
        app('config')->set($key, $settings);
    }

    /**
     * Process the array of settings, puts value into array
     *
     * @param  array &$settings  Reference to settings
     * @return array             Modified settings object
     */
    protected function processSettings(&$settings)
    {
        foreach ($settings as $key => $setting) {
            $settings[$key] = $this->processSetting($setting);
        }

        return $settings;
    }

    /**
     * Process the actual setting, check if it is what we expect and validate the data
     *
     * @param  Setting $setting  Setting object
     * @return mixed             Value returned by value method
     */
    protected function processSetting($setting)
    {
        if (is_subclass_of($setting, Setting::class)) {
            $setting->validate();
            return $setting->value();
        }

        return $setting;
    }

    /**
     * Get a some settings
     *
     * @return array
     */
    public function get($setting)
    {
        return app('config')->get($setting);
    }

    /**
     * Get all of the settings as json
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->settings);
    }
}
