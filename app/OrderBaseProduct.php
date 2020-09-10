<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBaseProduct extends Model
{
    protected $table = 'mshop_order_base_product';

    public $timestamps = false;


    public function site(){
        return $this->belongsTo('App\LocaleSite','siteid','siteid');
    }

    public function attribute(){
        return $this->hasOne('App\OrderBaseProductAttribute','ordprodid','id');
    }

    public function product_by_code(){
        return $this->hasOne('App\Product','code','prodcode');
    }

    public function product_by_id(){
        return $this->hasOne('App\Product','id','prodid');
    }

    public function order_base(){
        return $this->belongsTo('App\OrderBase', 'baseid','id');
    }

    public function order_base_product_attr(){
        return $this->hasMany('App\OrderBaseProductAttribute', 'ordprodid','id');
    }

    public function product_name(){
        // check if order product have attributes then set child product name
        if(count($this->order_base_product_attr) > 0){
            // get order base lang
            $order_lang = $this->order_base->langid;
            // get all id-s for text table
            $text_ids = $this->product_by_code->lists->where('domain', 'text')->pluck('refid');
            $product_name = Text::whereIn('id', $text_ids)
                ->where('type', 'name')
                ->where('langid', $order_lang)
                ->first()->content;

            return $product_name;
        }

        return $this->name;

    }

    public static function validateProdsFromCode($orderId, $parentId, $currentSiteId)
    {
        if($parentId < 2){
            return true;
        }else{
            $siteids = static::select('siteid')->where('baseid', $orderId)->pluck('siteid')->toArray();
            $prodExistFromCurrentSiteId = in_array($currentSiteId, $siteids);
            if($prodExistFromCurrentSiteId){
                return true;
            }
            return false;
        }
    }
}
