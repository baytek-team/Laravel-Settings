<?php

namespace Baytek\Laravel\Settings\Types;

use Baytek\Laravel\Settings\Setting;

use Exception;

class FloatSetting extends Setting
{
	/**
	 * Type of field we will be using
	 *
	 * @var string
	 */
	protected $type = 'number';

	/**
	 * List of attributes to be appended to the HTML field
	 *
	 * @var array
	 */
	protected $attributes = [
		'step' => 'any'
	];

	/**
	 * Unpack the value for use
	 *
	 * @return float  Return the value as float
	 */
	public function unpack()
	{
		return (float)$this->value;
	}

	/**
	 * Validate the data
	 *
	 * @return void
	 */
	public function validate()
	{
		parent::validate();

		if(!is_float($this->value)) {
			throw new Exception('The value was not properly set, expecting Integer');
		}
	}
}