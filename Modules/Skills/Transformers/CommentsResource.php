<?php

namespace Modules\Skills\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CommentsResource extends Resource
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
            'type'          => 'comments',
            'id'            => (string)$this->id,
            'attributes' => [
                'body' => $this->body,
            ],
            'relationships' => new CommentRelationshipResource($this),
            'links'      => [
                'self' => route('skills.comments.show', ['comment' => $this->id]),
            ],
        ];
    }
}
