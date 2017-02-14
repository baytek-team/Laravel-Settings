<?php

namespace Baytek\Laravel\Settings;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Baytek\Laravel\Settings\Setting;
use Baytek\Laravel\Settings\Models\Settings as SettingModel;
use Baytek\Laravel\Settings\SettingsProvider;

use Baytek\Laravel\Menu\MenuItem;

use View;

class SettingsController extends Controller
{
    protected $settings;

    public function __construct(SettingsProvider $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Show the index of all content with content type 'webpage'
     *
     * @return \Illuminate\Http\Response
     */
    public function settings(MenuItem $menu)
    {
        $settings = [];

        collect($this->settings->providers)->mapWithKeys(function($class, $key) use (&$settings)
        {
            $provider = new $class;
            $settings[$key] = collect($provider->getSettings())->only($provider->getPublicKeys())->all();
        });

        return view('Settings::index', compact('settings'));
    }

    public function save(Request $request)
    {
        collect($this->settings->providers)->mapWithKeys(function($class, $category) use ($request)
        {
            $provider = new $class;
            collect($provider->getSettings())->only($provider->getPublicKeys())->mapWithKeys(function($setting, $key) use ($category, $request, $provider)
            {
                $value = array_key_exists($key, $request->{$category}) ? $request->{$category}[$key] : false;
                $type = '';

                if(is_subclass_of($setting, Setting::class)) {
                    $value = $setting->pack($value);
                    $type = $setting->type();
                }

                SettingModel::updateOrCreate([
                    'key' => "$category.$key"
                ],
                [
                    'value' => $value,
                    'type' => $type,
                ]);
            });
        });

        return redirect(route('settings.index'));
    }
}