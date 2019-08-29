<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'position' => $this->position,
            'responsible_area' => $this->responsible_area,
            'entity_name' => $this->entity_name,
            //'matters' => $this->situation()->paginate(15),
            'matters' => MatterCollection::collection($this->situation)
       ];
    }
}
