<?php

namespace Core\Components\Controllers;


use Core\Components\Controllers\Base\AdminController;
use Core\Components\Models\AdminUser;
use Core\Components\Models\Permission;
use Core\Components\Models\Role;
use Core\Components\Resources\PermissionResource;

class PermissionAdminController extends AdminController
{
    private $permission;

    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $list = $this->permission->filter(
            ['guard_name' => $this->guard] + request()->input()
        )->paginateFilter();
        return PermissionResource::collection($list);
    }

    /**
     * 创建后台用户
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate(request(), [
            'name'       => 'required',
            'guard_name' => 'required',
        ]);
        return PermissionResource::make($this->permission->create(request(['guard_name', 'name'])));
    }

    /**
     * 更新用户信息
     * @param $id
     * @return PermissionResource
     */
    public function update($id)
    {
        $permission = $this->permission->find($id);
        if (request()->filled('name'))
            $permission->name = request('name');
        $permission->save();
        return PermissionResource::make($permission);
    }

    /**
     * 角色授权
     * @param $id
     */
    public function roleEmpowerment($id)
    {
        $role = Role::findById($id);
        $role->syncPermissions(Permission::whereIn('name', request('permissions', []))->get());
    }

    /**
     * 角色授权
     * @param $id
     */
    public function userEmpowerment($id)
    {
        $user = AdminUser::find($id);
        $user->syncPermissions(Permission::whereIn('name', request('permissions', []))->get());
    }

    /**
     * 绑定角色
     * @param $id
     */
    public function syncRoles($id)
    {
        $user = AdminUser::find($id);
        $user->syncRoles(Role::whereIn('name', request('roles', []))->get());
    }
}