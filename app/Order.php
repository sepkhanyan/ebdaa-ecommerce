<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'mshop_order';

    protected $fillable = array('*');

    public $timestamps = false;


    public function base_products()
    {
        return $this->hasMany('App\OrderBaseProduct', 'baseid', 'baseid');
    }

    public function base()
    {
        return $this->belongsTo('App\OrderBase', 'baseid', 'id');
    }

    public function base_address()
    {
        return $this->hasOne('App\OrderBaseAddress', 'baseid', 'baseid');
    }

    public function attribute_names()
    {
        $attributes = [];

        foreach ($this->original as $key => $value){
            $attributes[$key] = '';
        }

        return $attributes;

    }
}
