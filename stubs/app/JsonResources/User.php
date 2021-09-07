<?php

namespace App\JsonResources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'email' => $this->email,
            'role_id' => $this->role_id,
            'is_admin' => $this->is('admin'),
            'is_pending_activate' => $this->is_pending_activate,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // relationships
            'role' => new Role($this->role),
            'teams' => Team::collection($this->teams),
            'abilities' => Ability::collection($this->abilities),
        ];
    }
}
