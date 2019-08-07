<?php

namespace Modules\Skills\Entities;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [];
    protected $table = 'skills_articles';

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'autor_id','id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
