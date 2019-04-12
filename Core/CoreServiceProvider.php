<?php

namespace Core;


use Core\Components\Commands\CreateAdminUser;
use Core\Components\Commands\SocketServer;
use Illuminate\Support\ServiceProvider;
use Core\Components\Workerman\Instance\Websocket as WebsocketClient;
use Core\Components\Workerman\Interfaces\Websocket;

class CoreServiceProvider extends ServiceProvider
{

    public function boot()
    {
        app()->bind(Websocket::class, WebsocketClient::class);
        $this->loadMigrationsFrom(__DIR__ . '/../Databases/migrations');
        $this->mergeConfigFrom(__DIR__ . '/Components/Config/core.php', 'core');

        if (config('core.route')) {
            Core::routers();
        }

        if ($this->app->runningInConsole()) {

            $this->commands([
                CreateAdminUser::class,
                SocketServer::class,
            ]);
        }
    }
}