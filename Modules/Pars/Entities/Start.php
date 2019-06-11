<?php

namespace Modules\Pars\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Pars\Entities\start
 *
 * @property int $id
 * @property int $shop_id Ссылка на магазин
 * @property int $status_id Ссылка на статус
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class start extends Model
{
    protected $fillable = [];
    protected $table ='pars_starts';


}
