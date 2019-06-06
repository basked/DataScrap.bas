<?php

namespace Modules\Pars\Entities;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = [];
    protected $table='pars_actions';
    // связка с акциями
    public function products(){
        return $this->belongsToMany(Action::class,'pars_action_product','action_id','product_id')->withTimestamps();
    }
}
