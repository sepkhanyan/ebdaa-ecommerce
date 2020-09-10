<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBase extends Model
{
    protected $table = 'mshop_order_base';

    public $timestamps = false;


    public function order(){
        return $this->hasOne('App\Order','baseid','id');
    }

    public function order_base_product(){
        return $this->hasOne('App\OrderBaseProduct','baseid','id');
    }

    public function order_services(){
        return $this->hasMany('App\OrderBaseService','baseid','id');
    }

    public function order_address(){
        return $this->hasOne('App\OrderBaseAddress','baseid','id');
    }
}
