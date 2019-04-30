<?php

namespace Core\Components\Resources;


use Core\Components\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    public function toArray($request)
    {
        $data                = parent::toArray($request);
        $data['permissions'] = array_column($this->getAllPermissions()->toArray(), 'name');
        $data['roles']       = Role::query()->whereIn('name', $this->getRoleNames()->toArray())->value('id');
        return $data;
    }
}