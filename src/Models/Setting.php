<?php

namespace Baytek\Laravel\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public $timestamps = false;
}
