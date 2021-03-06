<?php

namespace Modules\Skills\Entities;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [];
    protected $table='skills_languages';

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
