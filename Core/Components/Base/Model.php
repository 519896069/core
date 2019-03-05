<?php

namespace Core\Components\Controllers\Base;

use Core\Components\Tools\StringTool;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use StringTool;

    protected $casts = ['id' => 'string'];

    /**
     * 设定用户的名字。
     *
     * @param  string $value
     * @return void
     */
    public function setIdAttribute($value)
    {
        $this->attributes['id'] = self::generate_uuid();
    }

}