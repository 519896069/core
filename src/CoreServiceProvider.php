<?php

namespace Core;


use Core\Components\Commands\CreateAdminUser;
use Core\Components\Commands\SocketServer;
use Illuminate\Support\ServiceProvider;
use Core\Components\Workerman\Instance\Websocket as WebsocketClient;
use Core\Components\Workerman\Interfaces\Websocket;
use Laravel\Passport\Passport;

class CoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->bind();
        $this->load();

        $this->publish();
    }

    public function boot()
    {
        Passport::tokensCan(array_merge(config('core.scopes'), config('route.scopes')));
    }

    private function publish()
    {
        $this->publishes([
            __DIR__ . '/Components/Config/route.php' => config_path('route.php'),
        ]);

    }

    private function bind()
    {
        app()->bind(Websocket::class, WebsocketClient::class);

    }

    private function load()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Databases/migrations');
        $this->mergeConfigFrom(__DIR__ . '/Components/Config/core.php', 'core');
        $this->mergeConfigFrom(__DIR__ . '/Components/Config/route.php', 'route');

        if ($this->app->runningInConsole())
            $this->commands([
                CreateAdminUser::class,
                SocketServer::class,
            ]);

        Core::routers();

    }
}