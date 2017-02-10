<?php

namespace Baytek\Laravel\Settings;

use Baytek\Laravel\Settings\Setting;
use Baytek\Laravel\Settings\Models\Settings as SettingModel;
use Settings;

trait Settable
{
    public function registerSettings($settings)
    {
        foreach($this->settings as $name => $class) {
            app('config')->set('settings.providers.'.$name, $class);
        }
    }
}