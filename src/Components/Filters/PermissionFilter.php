<?php

namespace Core\Components\Filters;


use EloquentFilter\ModelFilter;

class PermissionFilter extends ModelFilter
{
    public function guard_name($guard_name)
    {
        return $this->whereGuardName($guard_name);
    }
}