<?php

namespace Modules\Skills\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Modules\Skills\Entities\Autor;
use Modules\Skills\Entities\Comment;

class ArticleResource extends ResourceCollection
{
    /**
     * Преобразование коллекции ресурсов в массив.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => ArticleResource::collection($this->collection),
        ];
    }
    public function with($request)
    {
        $comments = $this->collection->flatMap(
            function ($article) {
                return $article->comments;
            }
        );
        $authors  = $this->collection->map(
            function ($article) {
                return $article->author;
            }
        );
        $included = $authors->merge($comments)->unique();
        return [
            'links'    => [
                'self' => route('articles.index'),
            ],
            'included' => $this->withIncluded($included),
        ];
    }
    private function withIncluded(Collection $included)
    {
        return $included->map(
            function ($include) {
                if ($include instanceof Autor) {
                    return new AutorResource($include);
                }
                if ($include instanceof Comment) {
                    return new CommentResource($include);
                }
            }
        );
    }
}
