<?php

namespace Baytek\Laravel\Settings;

// use Baytek\LaravelSettings;

class Settings
{
	protected $public    = [];
	protected $protected = [];
	protected $settings  = [];

	public function getSettings()
	{
		return $this->settings;
	}
}