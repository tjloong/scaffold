<?php

namespace App\JsonResources;

class Team extends JsonResource
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
            'description' => $this->description,

            // relationship

            // permissions
            'can' => [
                'update' => request()->user()->can('team.manage'),
                'delete' => request()->user()->can('team.manage'),
            ],
        ];
    }
}
