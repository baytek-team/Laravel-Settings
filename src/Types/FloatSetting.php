<?php

namespace Baytek\Laravel\Settings\Types;

use Baytek\Laravel\Settings\Setting;

use Exception;

class FloatSetting extends Setting
{
	protected $type = 'number';

	/**
	 * [process description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function unpack()
	{
		return (int)$this->value;
	}

	/**
	 * [validate description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function validate()
	{
		parent::validate();

		if(!is_float($this->value)) {
			throw new Exception('The value was not properly set, expecting Integer');
		}
	}
}