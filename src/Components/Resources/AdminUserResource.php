<?php

namespace Core\Components\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    public function toArray($request)
    {
        $data                = parent::toArray($request);
        $data['permissions'] = array_column($this->getAllPermissions()->toArray(), 'name');
        $data['roles']       = array_column($this->getAllRoles()->toArray(), 'id');
        return $data;
    }
}