<?php

namespace Core\Components\Commands;


use Core\Components\Base\Command;
use Core\Components\Workerman\Instance\WM;
use Illuminate\Support\Facades\DB;

class SocketServer extends Command
{
    protected $signature = "core:socket-server {action? : 动作}";

    protected $description = "开启socket服务器";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行任务方法
     * @param array $input
     * @return mixed
     */
    public function doJob(Array $input)
    {
        global $argv;
        if (!$action = $this->input->getArgument('action'))
            $action = $this->ask('请输入需要的动作,start|restart');
        $argv[1] = $action;
        $wm      = WM::getInstance(config('core.websocket.host'), config('core.websocket.port'));
        $wm->start();
    }
}
