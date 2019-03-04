<?php

namespace src\Core\Compoents\Controllers\Base;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $guard = '';

    public function __construct()
    {
        auth()->shouldUse($this->guard);
    }
}