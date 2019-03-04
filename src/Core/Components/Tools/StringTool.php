<?php

namespace src\Core\Compoents\Tools;

use Illuminate\Support\Str;

trait StringTool
{

    public static function getDatetime()
    {
        return date('Y-m-d H:i:s');
    }

    public static function getMicrotime()
    {
        return intval(microtime(true) * 1000);
    }

    public static function generate_uuid()
    {
        return str_replace('-', '', Str::uuid());
    }
}