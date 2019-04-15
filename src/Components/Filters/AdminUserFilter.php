<?php

namespace Core\Components\Filters;


use EloquentFilter\ModelFilter;

class AdminUserFilter extends ModelFilter
{
    public function account($account)
    {
        return $this->whereAccount($account);
    }
}