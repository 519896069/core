<?php

namespace Core;


use Core\Components\Commands\CreateAdminUser;
use Core\Components\Commands\SocketServer;
use Illuminate\Support\ServiceProvider;
use Core\Components\Workerman\Instance\Websocket as WebsocketClient;
use Core\Components\Workerman\Interfaces\Websocket;
use Route;

class CoreServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->routers();

        app()->bind(Websocket::class, WebsocketClient::class);
        $this->loadMigrationsFrom(__DIR__ . '/../Databases/migrations');
        $this->mergeConfigFrom(__DIR__ . '/Components/Config/core.php', 'core');

        if ($this->app->runningInConsole()) {

            $this->commands([
                CreateAdminUser::class,
                SocketServer::class,
            ]);
        }
    }

    public function routers()
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