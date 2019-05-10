<?php

namespace Core\Components\Workerman\Instance;


use Core\Components\Models\Connection;
use Core\Core;
use Illuminate\Support\Facades\DB;
use Workerman\Lib\Timer;
use Workerman\Worker;
use Core\Components\Workerman\Interfaces\Websocket as WebsocketInterface;

class Websocket implements WebsocketInterface
{

    public function onWorkStart(Worker $worker)
    {

    }

    public function onConnect($connection)
    {
        //token验证
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

    }
}
