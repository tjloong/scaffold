<?php

namespace App\JsonResources;

use Illuminate\Http\Resources\Json\JsonResource;

class Team extends JsonResource
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
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // relationship

            // permissions
            'can' => [
                'edit' => request()->user()->can('settings-team.manage'),
                'delete' => request()->user()->can('settings-team.manage'),
            ],
        ];
    }
}
