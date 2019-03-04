<?php
/**
 * Created by PhpStorm.
 * User: fengzepei
 * Date: 2019/2/12
 * Time: 14:15
 */

namespace Core\Components\Workerman\Instance;


use Workerman\Worker;
use Core\Components\Workerman\Interfaces\Websocket;

class WM
{
    private static $instance = null;
    const  PROCESS_NUMBER = 4;//进程数

    private $worker = null;

    private function __construct($host, $port)
    {
        $this->worker                = new Worker("websocket://$host:$port");
        $this->worker->count         = self::PROCESS_NUMBER;
        $websocket                   = app(Websocket::class);
        $this->worker->onWorkerStart = function ($worker) use ($websocket) {
            $websocket->onWorkStart($worker);
        };
        $this->worker->onConnect     = function ($connection) use ($websocket) {
            $websocket->onConnect($connection);
        };
        $this->worker->onMessage     = function ($connection, $data) use ($websocket) {
            $websocket->onMessage($connection, $data);
        };
        $this->worker->onClose       = function ($connection) use ($websocket) {
            $websocket->onClose($connection);
        };
    }

    public static function getInstance($host, $port)
    {
        self::$instance = self::$instance ?? new self($host, $port);
        return self::$instance;
    }

    public function getConnection($id = '')
    {
        return $this->worker->connections[$id] ?? $this->worker->connections;
    }

    public function start()
    {
        $this->worker->runAll();
    }

}