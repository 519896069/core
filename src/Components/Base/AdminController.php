<?php

namespace Core\Components\Controllers\Base;

class AdminController extends BaseController
{
    protected $guard = 'admin-api';

    protected $model_class;
    /** @type Model */
    protected $model;
    protected $resource_class;

    public function __construct()
    {
        parent::__construct();
        $this->model = app($this->model_class);
    }

    public function index()
    {
        $list = $this->model->filter(request()->input())->paginateFilter();
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

    public function delete($id)
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
}