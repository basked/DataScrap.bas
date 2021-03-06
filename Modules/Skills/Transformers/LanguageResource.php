<?php

namespace Modules\Skills\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class LanguageResource extends Resource
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
            'type' => 'languages',
            'id' => (string)$this->id,
            'attributes' => [
                'title' => $this->name,
                'description' => $this->description,
                'create_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'links'         => [
                'self' => route('skills.languages.show', ['language' => $this->id]),
            ],
        ];
    }




}

