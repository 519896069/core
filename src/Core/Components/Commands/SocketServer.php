<?php

namespace Core\Components\Commands;


use Core\Components\Base\Command;
use Core\Components\Models\AdminUser;
use Core\Components\Workerman\Instance\WM;

class SocketServer extends Command
{
    protected $signature = "core:socket-server {action}";

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
        $argv[1] = $this->input->getArgument('action');
        $wm      = WM::getInstance(config('socket.host'), config('socket.port'));
        $wm->start();
    }
}
