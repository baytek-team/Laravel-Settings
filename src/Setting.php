<?php

namespace Baytek\Laravel\Settings;

use Baytek\Laravel\Settings\Contracts\Setting as SettingContract;
use Exception;

abstract class Setting implements SettingContract
{
	/**
	 * Type of HTML field that will be rendered
	 *
	 * @var string
	 */
	protected $field = 'text';

	/**
	 * Value of the HTML field
	 *
	 * @var string
	 */
	protected $value;

	/**
	 * Possible options for the value of the field
	 *
	 * @var array
	 */
	protected $possibilities;

	/**
	 * Constructor
	 *
	 * @param mixed $config Array or string which sets the value of the field
	 */
	public function __construct($config)
	{
		if(is_array($config)) {
			foreach($config as $key => $value) {
				$this->$key = $value;
			}
		}
		else {
			$this->value = $config;
		}
	}

	/**
	 * Magic getter
	 *
	 * @param  string $property Property of this class to fetch
	 * @return mixed            Magical value
	 */
	public function __get($property)
	{
		if(!property_exists($this, $property)) {
			return null;
		}

		return $this->$property;
	}

	/**
	 * Returns current class name
	 *
	 * @return string The class name
	 */
    public function type()
    {
    	return get_called_class();
    }

	/**
	 * Pack the data for storage in the storage mechanism
	 *
	 * @param  string $value  Array of data
	 * @return string         Returns value
	 */
	public function pack($value)
	{
		return $value;
	}

	/**
	 * Unpack the value for use
	 *
	 * @return float  Return the value as float
	 */
	public function unpack()
	{
		return $this->value;
	}

	/**
	 * Validate the data
	 *
	 * @return void
	 */
	public function validate()
	{
		if(!is_null($this->possibilities)) {
			if(!in_array($this->value, $this->possibilities)) {
				throw new Exception('The value was not found in the list of possibilities');
			}
		}
	}
}