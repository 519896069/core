<?php

namespace Core;

use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Route;
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

    const POST     = 'post';
    const GET      = 'get';
    const RESOURCE = 'resource';

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


    public static function routers()
    {
        Route::middleware(['transaction'])->group(function () {
            Route::namespace(Core::$base_namespace)->group(function () {
                self::matchRoute(config('core.route.default'));
            });
            Route::namespace(config('route.namespace'))->group(function () {
                self::matchRoute(config('route'));
            });
        });
    }

    private static function matchRoute(Array $routes)
    {
        foreach ($routes as $route) {
            Route::group([
                'middleware' => $route['middleware'],
            ], function () use ($route) {
                foreach ($route['router'] as $router) {
                    [$method, $path, $action, $name] = $router;
                    switch ($method) {
                        case Core::RESOURCE:
                            Route::$method($path, $action)->only($name);
                            break;
                        default:
                            Route::$method($path, $action)->name($name);
                            break;
                    }
                }
            });
        }
    }
}