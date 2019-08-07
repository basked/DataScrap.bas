<?php

namespace Modules\Skills\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Skills\Entities\Article;
use Modules\Skills\Transformers\AutorResource;
use Modules\Skills\Transformers\CommentResource;


class ArticleRelationshipController extends Controller
{
    public function autor(Article $article)
    {

        return new AutorResource($article->autor);
    }

    public function comments(Article $article)
    {
        return new CommentResource($article->comments);
    }
}
