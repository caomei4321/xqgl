<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\Resource;

class MatterResource extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'address' => $this->address,
            'content' => $this->content,
            'image' => $this->image,
            'status' => $this->status,
            'accept' => $this->accept_num,
            'time_limit' => $this->time_limit,
            'work_num' => $this->work_num,
            'level' => $this->level,
            'type' => $this->type,
            'source' => $this->source,
            'is_secret' => $this->secret,
            'contact_name' => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'reply_remark' => $this->reply_remark,
            'suggestion' => $this->suggestion,
            'approval'  => $this->approval,
            'from'      => $this->from
        ];
    }
}
