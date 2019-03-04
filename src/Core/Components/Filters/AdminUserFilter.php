<?php

namespace src\Core\Compoents\Filters;


use EloquentFilter\ModelFilter;

class AdminUserFilter extends ModelFilter
{
    public function account($account)
    {
        return $this->whereAccount($account);
    }
}