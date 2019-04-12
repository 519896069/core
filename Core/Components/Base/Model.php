<?php

namespace Core\Components\Controllers\Base;

use Core\Components\Tools\StringTool;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use StringTool;

    protected $casts = ['id' => 'string'];

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();
        self::creating(function (Model $model) {
            $model{$model->getKeyName()} = self::generate_uuid();
        });
    }

}