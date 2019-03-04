<?php
/**
 * Created by PhpStorm.
 * User: fengzepei
 * Date: 2019/2/12
 * Time: 14:04
 */

namespace Core\Components\Workerman\Instance;


use Core\Components\Models\Connection;
use Core\Core;
use Illuminate\Support\Facades\DB;
use Workerman\Lib\Timer;
use Workerman\Worker;
use Core\Components\Workerman\Interfaces\Websocket as WebsocketInterface;

class Websocket implements WebsocketInterface
{
    const  HEARTBEAT_TIME     = 30;//超时时间
    const  HEARTBEAT_INTERVAL = 1;//心跳间隔时间

    public function onWorkStart(Worker $worker)
    {
        Connection::init();
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

    public function onConnect($connection)
    {
        //token验证
        $connection->onWebSocketConnect = function ($connection) {
            if (isset($_GET['token'])) {
                //修改链接状态
                Connection::updateOrCreate([
                    'user_id' => Core::getUidFormToken($_GET['token']),
                ], [
                    'connect_id' => $connection->id,
                    'worker_id'  => $connection->worker->id,
                    'user_id'    => Core::getUidFormToken($_GET['token']),
                    'status'     => Connection::ON,
                ]);
            } else {
                $connection->close();
            }
        };
    }

    public function onMessage($connection, $data)
    {
        DB::transaction(function () use ($connection, $data) {
            $data = json_decode($data, true);
            echo $data;
        });
    }

    public function onClose($connection)
    {
        //修改连接状态
        $connect_id              = $connection->id;
        $worker_id               = $connection->worker->id;
        $connectionModel         = Connection::whereConnectId($connect_id)
            ->whereWorkerId($worker_id)
            ->whereStatus(Connection::ON)
            ->first();
        $connectionModel->status = Connection::OFF;
        $connectionModel->save();
    }
}