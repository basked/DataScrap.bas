<?php

namespace Modules\Skills\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ArticleRelationshipResource extends Resource
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
            'author'   => [
                'links' => [
                    'self'    => route('skills.articles.relationships.autor', ['article' => $this->id]),
                    'related' => route('skills.articles.autor', ['article' => $this->id]),
                ],
                'data'  => new AutorIdentifierResource($this->autor),
            ],
            'comments' => (new ArticleCommentsRelationshipResource($this->comments))->additional(['article' => $this]),
        ];
    }

    public function with($request)
    {
        return [
            'links' => [
                'self' => route('skills.articles.index'),
            ],
        ];
    }
}
