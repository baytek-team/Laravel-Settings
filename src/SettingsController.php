<?php

namespace Baytek\Laravel\Settings;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

use View;

class SettingsController extends Controller
{
    use AuthorizesRequests;

    protected $settings;

    /**
     * Constructor
     *
     * @param SettingsService $settings Injecting the settings provider
     */
    public function __construct(SettingsService $settings)
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
        $settings = [];
        $namespace = config('settings.namespace');

        collect($this->settings->providers)->each(function ($class, $key) use (&$settings) {
            $provider = new $class;
            $settings[$key] = collect($provider->getSettings())->only($provider->getPublicKeys())->all();
        });

        return view('settings::index', compact('settings', 'namespace'));
    }

    /**
     * Save the settings
     *
     * @param  Request $request The settings request, this should have a request validator
     * @return \Illuminate\Routing\Redirector
     */
    public function save(Request $request)
    {
        collect($this->settings->providers)->each(function ($class, $category) use ($request) {
            $provider = new $class;

            collect($provider->getSettings())
                ->only($provider->getPublicKeys())
                ->each(function ($setting, $key) use ($category, $request, $provider) {

                    $value = array_key_exists($key, $request->{$category}) ? $request->{$category}[$key] : false;
                    $type = '';

                    if (is_subclass_of($setting, Types\Setting::class)) {
                        $value = $setting->pack($value);
                        $type = $setting->type();
                    }

                    Models\Setting::updateOrCreate(
                        [
                            'key' => "$category.$key"
                        ],
                        [
                            'value' => $value,
                            'type' => $type,
                        ]
                    );
                });
        });

        return redirect(route('settings.index'));
    }
}
