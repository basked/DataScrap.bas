<?php

namespace Modules\Skills\Entities;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $fillable = [];
    protected $table = 'skills_autors';

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
