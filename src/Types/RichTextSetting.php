<?php

namespace Baytek\Laravel\Settings\Types;

use Exception;

class RichTextSetting extends Setting
{
	protected $type = 'richtext';

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