<?php

namespace Core;

use Illuminate\Log\Logger;
use Log;
use Route;

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



    public static function routers()
    {
        Route::namespace(Core::$base_namespace)
            ->group(function () {
                Route::middleware(['transaction'])->group(function () {
                    Route::post('login', 'AuthAdminController@login')->name('login');

                    Route::middleware(['authenticate'])->group(function () {
                        Route::resource('user', 'UserAdminController')->only(['index', 'store', 'update', 'show']);
                        Route::resource('role', 'RoleAdminController')->only(['index', 'store', 'update']);
                        Route::resource('permission', 'PermissionAdminController')->only(['index', 'store', 'update']);

                        Route::post('roleEmpowerment/{id}', 'PermissionAdminController@roleEmpowerment')->name('role.empowerment');
                        Route::post('userEmpowerment/{id}', 'PermissionAdminController@userEmpowerment')->name('user.empowerment');
                        Route::post('syncRoles/{id}', 'PermissionAdminController@syncRoles')->name('sync.roles');
                        Route::post('logout', 'AuthAdminController@logout')->name('logout');
                    });
                });
            });
    }
}