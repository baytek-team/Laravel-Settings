<?php

namespace Baytek\Laravel\Settings\Types;

use Baytek\Laravel\Settings\Setting;

use Exception;

class StringSetting extends Setting
{
	/**
	 * Validate the data
	 *
	 * @return void
	 */
	public function validate()
	{
		parent::validate();

		if(!is_string($this->value)) {
			throw new Exception('The value was not properly set, expecting String');
		}
	}
}