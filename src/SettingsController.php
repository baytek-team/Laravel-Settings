<?php

namespace Baytek\Laravel\Settings;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Baytek\Laravel\Settings\Setting;
use Baytek\Laravel\Settings\Models\Settings as SettingModel;
use Baytek\Laravel\Settings\SettingsProvider;

use View;

class SettingsController extends Controller
{
    /**
     * Show the index of all content with content type 'webpage'
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        $settings = [];

        collect(SettingsProvider::$providers)->mapWithKeys(function($class, $key) use (&$settings)
        {
            $provider = new $class;
            $settings[$key] = collect($provider->getSettings())->only($provider->getPublicKeys())->all();
        });

        return view('Settings::index', compact('settings'));
    }

    public function save(Request $request)
    {
        collect(SettingsProvider::$providers)->mapWithKeys(function($class, $category) use ($request)
        {
            $provider = new $class;
            collect($provider->getSettings())->only($provider->getPublicKeys())->mapWithKeys(function($setting, $key) use ($category, $request, $provider)
            {
                $value = $request->{$category}[$key];
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