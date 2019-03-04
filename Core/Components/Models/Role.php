<?php

namespace Core\Components\Models;

use Core\Components\Filters\RoleFilter;
use EloquentFilter\Filterable;
use Spatie\Permission\Models\Role as PermissionsRole;

class Role extends PermissionsRole
{
    use Filterable;

    protected $hidden = ['guard_name', 'created_at', 'updated_at'];

    public function getModelFilterClass()
    {
        return RoleFilter::class;
    }
}