<?php

namespace Modules\Skills\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CommentRelationshipResource extends Resource
{
    public function toArray($request)
    {
        return [
            'autor'   => [
                'data'  => new AutorIdentifierResource($this->author),
            ],
        ];
    }
    public function with($request)
    {
        return [
            'links' => [
                'self' => route('skills.comments.index'),
            ],
        ];
    }
}
