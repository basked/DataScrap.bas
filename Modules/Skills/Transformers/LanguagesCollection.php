<?php

namespace Modules\Skills\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Route;

class LanguagesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'self' =>  route('skills.languages.index')
            ],
        ];
    }
}
