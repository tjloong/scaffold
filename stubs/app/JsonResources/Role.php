<?php

namespace App\JsonResources;

use Illuminate\Http\Resources\Json\JsonResource;

class Role extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'access' => $this->access,
            'is_system' => $this->is_system,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // relationship
            'abilities' => Ability::collection($this->abilities),

            // permissions
            'can' => [
                'edit' => request()->user()->can('settings-role.manage'),
                'delete' => request()->user()->can('settings-role.manage')
                    && !$this->is_system,
            ],
        ];
    }
}
