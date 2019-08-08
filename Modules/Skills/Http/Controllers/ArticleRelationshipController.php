<?php

namespace Modules\Skills\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Skills\Entities\Article;
use Modules\Skills\Entities\Language;
use Modules\Skills\Transformers\AutorResource;
use Modules\Skills\Transformers\CommentResource;
use Modules\Skills\Transformers\LanguageResource;


class ArticleRelationshipController extends Controller
{
    public function autor(Article $article)
    {
        return new AutorResource($article->autor);
    }

    public function language(Article $article)
    {
        return new LanguageResource($article->language);
    }

    public function comments(Article $article)
    {
        return new CommentResource($article->comments);
    }

}
