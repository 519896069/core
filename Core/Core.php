<?php

namespace Core;

use Illuminate\Log\Logger;
use Log;

class Core
{
    public static $base_namespace = 'Core\Components\Controllers';

    const ERROR   = "error";
    const ALTER   = "alter";
    const WARNING = "warning";
    const INFO    = "info";
    const NOTICE  = "notice";
    const DEBUG   = "debug";
    const LOG     = "log";

    /**
     * error
     * @param string $title
     * @param array $context
     * @param string $level
     */
    public static function printLog(string $title = '', Array $context = [], $level = self::INFO)
    {
        $log = config('app.log');
        if ($log) {
            $level = method_exists(Logger::class, $level) ? $level : self::INFO;
            Log::$level($title, $context);
        }
    }

    public static function getWebSocketToken($uid)
    {
        return base64_encode(md5($uid) . $uid);
    }

    public static function getUidFormToken($token)
    {
        return substr(base64_decode($token), 32, 32);
    }
}