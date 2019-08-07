<?php

namespace Modules\Skills\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class AutorResource extends Resource
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
            'type' => 'autor',
            'id' => (string)$this->id,
            'attributes' => [
                'first-name' => $this->first_name,
                'last-name' => $this->last_name,
                'nick-name'=>$this->nick_name,
                'email' => $this->email,
            ],
            'links' => [
                'self' => route('skills.autors.show', ['autors' => $this->id]),
            ],
        ];
    }
}
