<?php

namespace Baytek\Laravel\Settings;

use Baytek\Laravel\Settings\Contracts\Settings as SettingsContract;

class Settings implements SettingsContract
{
	protected $public    = [];
	protected $settings  = [];

	public function getSettings()
	{
		return $this->settings;
	}

	public function getPublicKeys()
	{
		return $this->public;
	}

	public function register($settings)
	{
		foreach($settings as $key => $setting) {
			$this->settings[$key] = $setting;
		}
	}
}