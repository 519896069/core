<?php

namespace src\Core\Compoents\Workerman\Interfaces;


use Workerman\Worker;

interface Websocket
{
    public function onWorkStart(Worker $worker);

    public function onConnect($connection);

    public function onMessage($connection, $data);

    public function onClose($connection);
}