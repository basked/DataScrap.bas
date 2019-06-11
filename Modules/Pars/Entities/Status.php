<?php

namespace Modules\Pars\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Pars\Entities\status
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class status extends Model
{
    protected $fillable = [];
    protected $table ='pars_statuses';
}
