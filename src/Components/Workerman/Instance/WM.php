<?php

namespace Core\Components\Workerman\Instance;


use Core\Components\Models\Connection;
use Core\Core;
use Illuminate\Support\Facades\DB;
use Workerman\Lib\Timer;
use Workerman\Worker;
use Core\Components\Workerman\Interfaces\Websocket;

class WM
{
    private static $instance = null;
    const  PROCESS_NUMBER = 4;//进程数

    const  HEARTBEAT_TIME     = 30;//超时时间
    const  HEARTBEAT_INTERVAL = 1;//心跳间隔时间

    private $worker = null;

    private function __construct($host, $port)
    {
        $this->worker                = new Worker("websocket://$host:$port");
        $this->worker->count         = self::PROCESS_NUMBER;
        $websocket                   = app(Websocket::class);
        $this->worker->onWorkerStart = function ($worker) use ($websocket) {
            $this->heartbeat($worker);
            $websocket->onWorkStart($worker);
        };
        $this->worker->onConnect     = function ($connection) use ($websocket) {
            $connection->onWebSocketConnect = function ($connection) {
                if (isset($_GET['token'])) {
                    //修改链接状态
                    Connection::query()->updateOrCreate([
                        'user_id' => Core::getUidFormToken($_GET['token']),
                    ], [
                        'connect_id' => $connection->id,
                        'worker_id'  => $connection->worker->id,
                        'user_id'    => Core::getUidFormToken($_GET['token']),
                        'status'     => Connection::ON,
                    ]);
                    DB::commit();
                } else {
                    $connection->close();
                }
            };
            $websocket->onConnect($connection);
        };
        $this->worker->onMessage     = function ($connection, $data) use ($websocket) {
            $websocket->onMessage($connection, $data);
        };
        $this->worker->onClose       = function ($connection) use ($websocket) {
            $this->closeConnect($connection);
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

    public function closeConnect($connection)
    {
        $connect_id      = $connection->id;
        $worker_id       = $connection->worker->id;
        $connectionModel = Connection::whereConnectId($connect_id)
            ->whereWorkerId($worker_id)
            ->whereStatus(Connection::ON)
            ->first();
        if ($connectionModel) {
            $connectionModel->status = Connection::OFF;
            $connectionModel->save();
            DB::commit();
        }
    }

    private function heartbeat($worker)
    {
        Timer::add(self::HEARTBEAT_INTERVAL, function () use ($worker) {
            $time_now = time();
            foreach ($worker->connections as $connection) {
                $connection->send(json_encode(['event' => 'heartbeat', 'data' => []]));
                // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                if (empty($connection->lastMessageTime)) {
                    $connection->lastMessageTime = $time_now;
                    continue;
                }
                // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
                if ($time_now - $connection->lastMessageTime > self::HEARTBEAT_TIME) {
                    $connection->close();
                }
            }
        });
    }
}