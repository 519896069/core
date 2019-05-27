<?php

namespace Core\Components\Resources;


use Core\Components\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['tree'] = Role::whereId($data['id'])->first()->getAllPermissions()->pluck('name');
        return $data;
    }
}