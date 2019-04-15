<?php

namespace Core\Components\Controllers;


use Core\Components\Controllers\Base\AdminController;
use Core\Components\Models\Role;
use Core\Components\Resources\RoleResource;

class RoleAdminController extends AdminController
{
    private $role;

    public function __construct(Role $role)
    {
        parent::__construct();
        $this->role = $role;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $list = $this->role->filter(
            ['guard_name' => $this->guard] + request()->input()
        )->paginateFilter();
        return RoleResource::collection($list);
    }

    /**
     * 创建后台用户
     * @return RoleResource
     */
    public function store()
    {
        $this->validate(request(), [
            'name'       => 'required',
            'guard_name' => 'required',
        ]);
        return RoleResource::make($this->role->create(request(['guard_name', 'name'])));
    }

    /**
     * 更新用户信息
     * @param $id
     * @return RoleResource
     */
    public function update($id)
    {
        $role = $this->role->find($id);
        if (request()->filled('name'))
            $role->name = request('name');
        $role->save();
        return RoleResource::make($role);
    }
}