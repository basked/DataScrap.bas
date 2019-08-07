<?php

namespace Modules\Skills\Entities;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [];
    protected $table = 'skills_comments';

    public function author()
    {
        return $this->belongsTo(Autor::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

}
