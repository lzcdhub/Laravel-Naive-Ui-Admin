<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['isDefault'] = false;
        $data['status'] = "normal";
        $data['menu_keys'] = $this->getPermissionIds();
        return $data;
    }

    public function getPermissionIds()
    {
        $ids = [];
        foreach ($this->permissions as $val) {
            array_push($ids,$val['id']);
        }
        return $ids;
    }
}
