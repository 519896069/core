<?php

namespace src\Core\Compoents\Filters;


use EloquentFilter\ModelFilter;

class RoleFilter extends ModelFilter
{
    public function guard_name($guard_name)
    {
        return $this->whereGuardName($guard_name);
    }
}