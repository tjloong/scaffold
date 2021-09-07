<?php

namespace App\JsonResources;

use Illuminate\Http\Resources\Json\JsonResource;

class Ability extends JsonResource
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
            'module' => $this->module,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // relationship
            'pivot' => $this->pivot,
        ];
    }
}
