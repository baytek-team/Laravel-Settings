<?php

namespace Baytek\Laravel\Settings;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Baytek\Laravel\Settings\Setting;
use Baytek\Laravel\Settings\Models\Settings as SettingModel;
use Baytek\Laravel\Settings\SettingsProvider;

use Baytek\Laravel\Menu\Menu;
use Baytek\Laravel\Menu\Link;

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
    public function settings()
    {
        $menu = new Menu([
            new Menu([
                new Link('Settings', ['location' => 'settings.save', 'type' => 'route'])
            ], [
                'class' => 'ui dropdown item',
                'before' => 'Users <i class="dropdown icon"></i><div class="menu">',
                'after' => '</div>'
            ])
        ], [
            'prepend' => '<div class="ui container inverted">',
            'append' => '</div>',
        ]);

        $settings = [];

        collect($this->settings->providers)->each(function ($class, $key) use (&$settings) {
            $provider = new $class;
            $settings[$key] = collect($provider->getSettings())->only($provider->getPublicKeys())->all();
        });

        return view('settings::index', compact('settings', 'menu'));
    }

    public function save(Request $request)
    {
        collect($this->settings->providers)->each(function ($class, $category) use ($request) {
            $provider = new $class;

            collect($provider->getSettings())
                ->only($provider->getPublicKeys())
                ->each(function ($setting, $key) use ($category, $request, $provider) {

                    $value = array_key_exists($key, $request->{$category}) ? $request->{$category}[$key] : false;
                    $type = '';

                    if (is_subclass_of($setting, Setting::class)) {
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
