<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBaseService extends Model
{
    protected $table = 'mshop_order_base_service';

    public $timestamps = false;

    public function service_list(){
        return $this->hasMany('App\ServiceList', 'parentid','servid');
    }
}
