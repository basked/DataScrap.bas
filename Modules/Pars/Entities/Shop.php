<?php

namespace Modules\Pars\Entities;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'pars_shops';
    protected $fillable = ['name','url'];
    protected $visible = ['id','name','url'];

    public function categories()
    {
       return $this->hasMany(Category::class,'shop_id' , 'id');
    }
}
