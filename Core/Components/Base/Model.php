<?php

namespace Core\Components\Controllers\Base;

use Core\Components\Tools\StringTool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use StringTool;

    protected $casts = ['id' => 'string'];

    public $incrementing = true;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected function performInsert(Builder $query)
    {
        $this->setAttribute($this->getKeyName(), self::generate_uuid());
        parent::performInsert($query);
    }

}