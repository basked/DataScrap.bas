<?php

namespace Modules\Pars\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Pars\Entities\Shop
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Pars\Entities\Category[] $categories
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereUrl($value)
 * @mixin \Eloquent
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereActive($value)
 */
class Shop extends Model
{
    protected $table = 'pars_shops';
    protected $fillable = ['id','name','url','active'];
    protected $visible = ['id','name','url','active'];

    public function categories()
    {
       return $this->hasMany(Category::class,'shop_id' , 'id');
    }
}
