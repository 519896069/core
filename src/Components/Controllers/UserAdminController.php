<?php

namespace Core\Components\Controllers;


use Core\Components\Controllers\Base\AdminController;
use Core\Components\Controllers\Base\AppException;
use Core\Components\Models\AdminUser;
use Core\Components\Resources\AdminUserResource;
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
     * @return AdminUserResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate(request(), [
            'account'  => 'required|min:5|unique:admin_users',
            'password' => 'required|min:5',
            'username' => 'required',
        ]);
        $user = $this->adminUser->createData(request(['account', 'password', 'username', 'administer']));
        if ($roles = request('roles')) $user->syncRoles($roles);
        return AdminUserResource::make($user);
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
        $user->administer = request('administer');
        $user->save();
        if ($roles = request('roles')) $user->syncRoles($roles);
        return AdminUserResource::make($user);
    }

    public function show($id)
    {
        $user = $this->adminUser->query()->find($id);
        if (!$user) throw new AppException(-1, '用户不存在');
        return AdminUserResource::make($user);
    }

    public function info()
    {
        if (!$user = auth()->user()) throw new AppException(-1, '用户不存在');
        return AdminUserResource::make($user);
    }


}