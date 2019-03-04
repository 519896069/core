<?php

namespace src\Core\Compoents\Commands;


use src\Core\Compoents\Base\Command;
use src\Core\Compoents\Models\AdminUser;

class CreateAdminUser extends Command
{
    protected $signature = "core:create-admin";

    protected $description = "创建后台用户";

    private $adminUser;

    public function __construct(AdminUser $adminUser)
    {
        parent::__construct();
        $this->adminUser = $adminUser;
    }

    /**
     * 执行任务方法
     * @param array $input
     * @return mixed
     */
    public function doJob(Array $input)
    {
        $username = $this->ask('请输入用户名');
        $account  = $this->ask('请输入登录账号');
        $password = $this->secret('请输入密码');
        $this->info('账号创建中...');
        $this->adminUser->createData([
            'username'   => $username,
            'account'    => $account,
            'password'   => $password,
            'administer' => AdminUser::ADMIN,
        ]);
        $this->info('创建成功.');
    }
}