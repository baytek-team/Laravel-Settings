<?php

namespace Baytek\Laravel\Settings\Types;

use Exception;

class IntegerSetting extends Setting
{
    protected $type = 'number';

    /**
     * Unpack the value for use
     *
     * @return int  Return the value as integer
     */
    public function unpack()
    {
        return (int)$this->value;
    }

    /**
     * Validate the data
     *
     * @return void
     */
    public function validate()
    {
        parent::validate();

        if (!is_integer($this->value)) {
            throw new Exception('The value was not properly set, expecting Integer');
        }
    }
}
