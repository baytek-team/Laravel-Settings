<?php

namespace Baytek\Laravel\Settings\Types;

use Baytek\Laravel\Settings\Setting;

use Exception;

class ArraySetting extends Setting
{
	protected $type = 'text';

	/**
	 * Pack the data for storage in the storage mechanism
	 * @param  array $value  Array of data
	 * @return string        Serialized string of data
	 */
	public function pack($value)
	{
		return serialize($value);
	}

	/**
	 * Unpack the value for use
	 *
	 * @return array   Return the value as an unserialized array
	 */
	public function unpack()
	{
		return unserialize($this->value);
	}

	/**
	 * Validate the data
	 *
	 * @return void
	 */
	public function validate()
	{
		parent::validate();

		if(!is_array($this->value)) {
			throw new Exception('The value was not properly set, expecting Array. Got: ' . $this->value);
		}
	}
}