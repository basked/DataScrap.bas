<?php

namespace Modules\Skills\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class LanguageRelationshipResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'language'   => [
                'links' => [
                    'self'    => route('languages.relationships.author', ['language' => $this->id]),
                    'related' => route('language.author', ['article' => $this->id]),
                ],
                'data'  => new AuthorIdentifierResource($this->author),
            ],
            'comments' => (new ArticleCommentsRelationshipResource($this->comments))->additional(['article' => $this]),
        ];
    }
}
