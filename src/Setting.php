<?php

namespace Baytek\Laravel\Settings;

use Baytek\Laravel\Settings\Contracts\Setting as SettingContract;
use Exception;

abstract class Setting implements SettingContract
{
	protected $field = 'text';
	protected $value;
	protected $possibilities;

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

	public function __get($property)
	{
		if(!property_exists($this, $property)) {
			return null;
		}

		return $this->$property;
	}

    public function type()
    {
    	return get_called_class();
    }

	/**
	 * [store description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function pack($value)
	{
		return $value;
	}

	/**
	 * [process description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function unpack()
	{
		return $this->value;
	}

	public function validate()
	{
		if(!is_null($this->possibilities)) {
			if(!in_array($this->value, $this->possibilities)) {
				throw new Exception('The value was not found in the list of possibilities');
			}
		}
	}
}