<?php

namespace Modules\Skills\Http\Controllers;

use Modules\Skills\Entities\Article;
use Modules\Skills\Transformers\AutorResource;
use Modules\Skills\Transformers\CommentsResource;
use Illuminate\Routing\Controller;

class ArticleRelationshipController extends Controller
{
    public function author(Article $article)
    {
        return new AutorResource($article->autor);
    }

    public function comments(Article $article)
    {
        return new CommentsResource($article->comments);
    }
}
