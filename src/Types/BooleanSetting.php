<?php

namespace Baytek\Laravel\Settings\Types;

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
     * Validate the data
     *
     * @return void
     */
    public function validate()
    {
        parent::validate();

        if (!is_bool($this->value)) {
            throw new Exception('The value was not properly set, expecting Boolean');
        }
    }
}
