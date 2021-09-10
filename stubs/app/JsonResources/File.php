<?php

namespace App\JsonResources;

use Illuminate\Http\Resources\Json\JsonResource;

class File extends JsonResource
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
            'mime' => $this->mime,
            'type' => $this->type,
            'size' => $this->size,
            'url' => $this->url,
            'data' => $this->data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // relationship
        ];
    }
}
