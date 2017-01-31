<?php

namespace Baytek\Laravel\Settings;

// use Baytek\LaravelSettings;

class Settings //extends Settings
{
	protected $public = [
		'per_page'
	];

	protected $settings = [
		'per_page' => 10
	];

	public function getSettings()
	{
		return $this->public;
	}
}