<?php

namespace src\Core\Compoents\Controllers;


use src\Core\Compoents\Controllers\Base\AdminController;
use src\Core\Compoents\Models\AdminUser;
use src\Core\Compoents\Resources\AdminUserResource;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends AdminController
{
    private $adminUser;

    public function __construct(AdminUser $adminUser)
    {
        parent::__construct();
        $this->adminUser = $adminUser;
    }

    public function index()
    {
        $list = $this->adminUser->filter(request()->input())->paginateFilter();
        return AdminUserResource::collection($list);
    }

    /**
     * 创建后台用户
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate(request(), [
            'account'  => 'required|min:5|unique:admin_users',
            'password' => 'required|min:5',
            'username' => 'required',
        ]);
        return AdminUserResource::make($this->adminUser->createData(request(['account', 'password', 'username'])));
    }

    /**
     * 更新用户信息
     * @param $id
     * @return AdminUserResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id)
    {
        $this->validate(request(), [
            'account'  => 'min:5',
            'password' => 'min:5',
        ]);
        $user = $this->adminUser->find($id);
        if (request()->filled('username'))
            $user->username = request('username');
        if (request()->filled('password'))
            $user->password = Hash::make(request('password'));
        $user->save();
        return AdminUserResource::make($user);
    }

    public function show($id)
    {
        $user = $this->adminUser->find($id);
        return AdminUserResource::make($user);
    }
}