<?php

namespace Core\Components\Commands;

use Core\Components\Workerman\Instance\WM;
use Illuminate\Console\Command;

class SocketServer extends Command
{
    protected $signature = "core:socket-server {action? : 动作}";

    protected $description = "开启socket服务器";

    /**
     * 执行任务方法
     * @return mixed
     */
    public function handle()
    {
        global $argv;
        if (!$action = $this->argument('action'))
            $action = $this->ask('请输入需要的动作,start|restart');
        $argv[1] = $action;
        $wm      = WM::getInstance(config('core.websocket.host'), config('core.websocket.port'));
        $wm->start();
    }
}
