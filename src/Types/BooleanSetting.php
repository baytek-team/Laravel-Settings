<?php

namespace Baytek\Laravel\Settings\Types;

use Baytek\Laravel\Settings\Setting;

use Exception;

class BooleanSetting extends Setting
{
	protected $type = 'radio';
	/**
	 * [process description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function unpack()
	{
		return (bool)$this->value == 'on';
	}

	/**
	 * [validate description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function validate()
	{
		parent::validate();

		if(!is_bool($this->value)) {
			throw new Exception('The value was not properly set, expecting Boolean');
		}
	}
}