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
        $this->bind();
        $this->load();
        $this->publish();
    }

    private function publish()
    {
        $this->publishes([
            __DIR__ . '/Components/Config/core.php' => config_path('core.php'),
        ]);

    }

    private function bind()
    {
        app()->bind(Websocket::class, WebsocketClient::class);

    }

    private function load()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Databases/migrations');
        $this->mergeConfigFrom(__DIR__ . '/Components/Config/core.php', 'core');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateAdminUser::class,
                SocketServer::class,
            ]);
        }

        if (config('core.route', false)) {
            Core::routers();
        }
    }
}