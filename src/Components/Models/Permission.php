<?php

namespace Core\Components\Models;

use Core\Components\Filters\PermissionFilter;
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

    public static function getPermissionsFromName($names)
    {
        $permissions = [];
        if (is_array($names)) {
            foreach ($names as $name) {
                $permissions[] = Permission::firstOrCreate(['name' => $name,], ['name' => $name]);
            }
        } else {
            $permissions[] = Permission::firstOrCreate(['name' => $names,], ['name' => $names]);
        }
        return $permissions;
    }
}