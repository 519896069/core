<?php

namespace Core\Components\Controllers\Base;

use Core\Components\Tools\StringTool;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use StringTool;

    protected $casts = ['id' => 'string'];

    /**
     * @param $data
     * @return mixed
     */
    public function createData($data)
    {
        $data['id']         = self::generate_uuid();
        return parent::create($data);
    }
}