<?php

namespace src\Core\Compoents\Controllers\Base;

use src\Core\Compoents\Tools\StringTool;
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