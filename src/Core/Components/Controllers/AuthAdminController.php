<?php

namespace src\Core\Compoents\Controllers;

use src\Core\Compoents\Controllers\Base\AdminController;
use src\Core\Compoents\Models\AdminUser;
use Core\Core;


class AuthAdminController extends AdminController
{
    private $adminUser;

    /**
     * LoginController constructor.
     * @param AdminUser $adminUser
     */
    public function __construct(AdminUser $adminUser)
    {
        parent::__construct();
        $this->adminUser = $adminUser;
    }

    /**
     * 登录
     */
    public function login()
    {
        if (!$user = $this->adminUser->filter(['account' => request('account')])->first())
            return response()->make('file not found!', 404);
        if (!$user->checkPassword(request('password')))
            return response()->make('Failure of authorization', 401);
        $user->ref();
        return response()->json([
            'code' => 0,
            'msg'  => 0,
            'data' => [
                'token'    => $user->createToken('admin-api', ['admin-api'])->accessToken,
                'ws_token' => Core::getWebSocketToken($user->id),
            ],
        ]);
    }

    /**
     * 登出
     */
    public function logout()
    {
        auth()->user()->token()->delete();
        return response()->json([
            'code' => 0,
            'msg'  => 0,
            'data' => [],
        ]);
    }
}