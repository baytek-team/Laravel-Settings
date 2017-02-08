<?php

namespace Baytek\Laravel\Settings\Contracts;

interface Setting
{
	public function pack($value);
	public function unpack();
	public function value();
	public function type();
	public function validate();
}