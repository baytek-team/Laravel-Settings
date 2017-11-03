<?php

namespace Baytek\Laravel\Settings\Contracts;

interface SettingContract
{
    public function pack($value);
    public function unpack();
    public function type();
    public function validate();
}
