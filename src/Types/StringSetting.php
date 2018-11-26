<?php

namespace Baytek\Laravel\Settings\Types;

use Baytek\Laravel\Settings\Setting;

use Exception;

class StringSetting extends Setting
{
	protected $type = 'text';

	/**
	 * [validate description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function validate()
	{
		parent::validate();

		if(!is_string($this->value)) {
			throw new Exception('The value was not properly set, expecting String');
		}
	}
}