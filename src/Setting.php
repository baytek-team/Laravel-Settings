<?php

namespace Baytek\Laravel\Settings;

use Baytek\Laravel\Settings\Contracts\Setting as SettingContract;
use Exception;

abstract class Setting implements SettingContract
{
	protected $value;
	protected $possibilities;

	public function __construct($value, $possibilities = null)
	{
		$this->value = $value;
		$this->possibilities = $possibilities;
	}

	public function value()
    {
        return $this->value;
    }

    public function possibilities()
    {
        return $this->possibilities;
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