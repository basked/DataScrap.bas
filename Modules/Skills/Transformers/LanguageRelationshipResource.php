<?php

namespace Modules\Skills\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class LanguageRelationshipResource extends Resource
{
    public function toArray($request)
    {
        return [
            'language'   => [
                'data'  => new AutorIdentifierResource($this->author),
            ],
        ];
    }
    public function with($request)
    {
        return [
            'links' => [
                'self' => route('skills.language.index'),
            ],
        ];
    }
}
