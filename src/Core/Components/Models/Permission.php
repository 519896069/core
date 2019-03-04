<?php

namespace src\Core\Compoents\Models;

use src\Core\Compoents\Filters\PermissionFilter;
use EloquentFilter\Filterable;
use Spatie\Permission\Models\Permission as PermissionsPermission;

class Permission extends PermissionsPermission
{
    use Filterable;

    protected $hidden = ['guard_name', 'created_at', 'updated_at'];

    public function getModelFilterClass()
    {
        return PermissionFilter::class;
    }
}