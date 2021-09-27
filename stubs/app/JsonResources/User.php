<?php

namespace App\JsonResources;

class User extends JsonResource
{
    /**
     * Get the resource
     *
     * @return array
     */
    public function getResource($request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'is_admin' => $this->is('admin'),
            'is_pending_activate' => $this->is_pending_activate,
            'email_verified_at' => $this->email_verified_at,

            // relationships
            'role' => new Role($this->role),
            'teams' => Team::collection($this->teams),
            'abilities' => Ability::collection($this->abilities),

            // permissions
            'can' => [
                'update' => request()->user()->can('user.manage'),
                'delete' => request()->user()->can('user.manage')
                    && $this->id !== request()->user()->id,
            ],
        ];
    }
}
