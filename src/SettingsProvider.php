<?php

namespace Baytek\Laravel\Settings;

class SettingsProvider
{
	public static $providers = [];

	public function register($name, $provider)
	{
		self::$providers[$name] = $provider;
	}
}