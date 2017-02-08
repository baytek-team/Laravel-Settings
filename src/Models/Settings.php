<?php

namespace Baytek\Laravel\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public $timestamps = false;
}
