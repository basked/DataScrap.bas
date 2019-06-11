<?php

namespace Modules\Pars\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Pars\Entities\Action
 *
 * @property int $id
 * @property int $action_id
 * @property string $name
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Pars\Entities\Action[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Action extends Model
{
    protected $fillable = [];
    protected $table='pars_actions';
    // связка с акциями
    public function products(){
        return $this->belongsToMany(Action::class,'pars_action_product','action_id','product_id')->withTimestamps();
    }
}
