<?php

namespace Baytek\Laravel\Settings\Types;

use Baytek\Laravel\Settings\Setting;

use Exception;

class ArraySetting extends Setting
{
	protected $type = 'text';

	/**
	 * [store description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function pack($value)
	{
		return serialize($value);
	}

	/**
	 * [process description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function unpack()
	{
		return unserialize($this->value);
	}

	/**
	 * [validate description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function validate()
	{
		parent::validate();

		if(!is_array($this->value)) {
			throw new Exception('The value was not properly set, expecting Array. Got: ' . $this->value);
		}
	}
}