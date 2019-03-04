<?php

namespace Core\Components\Base;

use DB;
use Illuminate\Console\Command as BaseCommand;

abstract class Command extends BaseCommand
{
    /**
     * @throws \Throwable
     */
    public function handle()
    {
        DB::transaction(function () {
            $this->doJob($this->arguments());
        });
    }

    /**
     * 执行任务方法
     * @param array $input
     * @return mixed
     */
    abstract public function doJob(Array $input);
}
