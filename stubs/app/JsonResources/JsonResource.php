<?php

namespace App\JsonResources;

use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->getResource($request);

        if (isset($this->id)) $resource['id'] = $this->id;
        if (isset($this->created_at)) $resource['created_at'] = $this->created_at;
        if (isset($this->updated_at)) $resource['updated_at'] = $this->updated_at;

        return $resource;
    }
}