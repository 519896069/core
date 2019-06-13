<?php

namespace Core\Components\Controllers\Base;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdminController extends BaseController
{
    protected $guard = 'admin-api';

    protected $model_class;
    /** @type Model */
    protected $model;
    protected $resource_class;

    protected $validator = [
        'validate' => [],
        'message'  => [],
    ];

    const ERROR_MESSAGE = [
        'required' => ':attribute 必须填写',
        'max'      => ':attribute 超过了指定长度 :max',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->model = app($this->model_class);
    }

    public function index()
    {
        $list = $this->model->filter(request()->input())->orderBy('created_at')->paginateFilter();
        return ($this->resource_class)::collection($list);
    }

    public function store()
    {
        $data = request($this->model->getFillable());
        $data = $this->storeData($data);
        return ($this->resource_class)::make(
            $this->model->newQuery()->create($data)
        );
    }

    public function update($id)
    {
        $data  = request($this->model->getFillable());
        $query = $this->model->newQuery()->whereId($id)->firstOrFail();
        $data  = $this->updateData($data, $query);
        $query->update($data);
        return ($this->resource_class)::make($query);
    }

    public function show($id)
    {
        return ($this->resource_class)::make(
            $this->model->whereId($id)->first()
        );
    }

    public function destroy($id)
    {
        $model = $this->model->newQuery()->findOrFail($id);
        $model->delete();
    }

    protected function storeData($data)
    {
        return $data;
    }

    protected function updateData($data, Model $query)
    {
        return $data;
    }


    /**
     * @param $message
     * @throws HttpException
     */
    public function throwAppException($message)
    {
        throw new HttpException(Response::HTTP_BAD_REQUEST, $message);
    }

    /**
     * @param $data
     * @throws HttpException
     */
    public function valid($data)
    {
        $validator = Validator::make($data, $this->validator['validate'], self::ERROR_MESSAGE, $this->validator['message']);
        if ($validator->errors()->isNotEmpty()) {
            $this->throwAppException(implode(',', array_values($validator->errors()->all())));
        }
    }
}