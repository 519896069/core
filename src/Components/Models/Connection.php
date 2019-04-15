<?php

namespace Core\Components\Models;


use Core\Components\Controllers\Base\Model;

class Connection extends Model
{
    const ON  = 1;
    const OFF = 0;

    protected $fillable = [
        'id', 'connect_id', 'user_id', 'worker_id', 'status'
    ];

    protected $casts = ['id' => 'string'];

    public static function init()
    {
        (new Connection)->update(['status' => self::OFF]);
    }
}