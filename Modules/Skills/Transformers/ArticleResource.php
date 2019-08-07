<?php

namespace Modules\Skills\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Skills\Entities\Article;

class ArticleResource extends Resource
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
            'type'          => 'articles',
            'id'            => (string)$this->id,
            'attributes'    => [
                'title' => $this->title,
                'body' => $this->body,
                'created_at' => $this->created_at,
            ],
            'relationships' => new ArticleRelationshipResource($this),
            'links'         => [
                'self' => route('skills.articles.show', ['article' => $this->id]),
            ],
        ];
    }
}
