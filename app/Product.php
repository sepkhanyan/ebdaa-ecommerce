<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'mshop_product';

    public $timestamps = false;

    public function lists()
    {
        return $this->hasMany('App\ProductList', 'parentid', 'id');
    }

    public function price(){

        $price = null;

        $price_id = $this->lists->where('domain', 'price')->first();

        if($price_id){

            $price = Price::where('id', $price_id->refid)->first();

        }

        return $price;
    }

    public static function getProdNameFromId($id)
    {
        $data =  static::select('label')
        ->where('id', $id)
        ->first();
        if ($data) {
            return $data->label;
        }
        return 'No Name';
    }

    public static function getProductById($id)
    {
        return static::select('id', 'siteid', 'code', 'label', 'pos')
        ->where('id', $id)
        ->paginate();
    }

    public function stock()
    {
        return $this->hasOne('App\ProductStock', 'productcode', 'code');
    }

    public function catalogs()
    {
        return $this->hasMany('App\CatalogList', 'refid', 'id');
    }

    public function enableCatalogs()
    {
        return $this->hasMany('App\CatalogList', 'refid', 'id')->whereHas('enableCatalog');
    }

    public function properties()
    {
        return $this->hasMany('App\ProductProperty', 'parentid', 'id');
    }

    public function description($lang = null)
    {
        if($lang && $lang == 'ar'){
            $text = $this->getTexts()->whereHas('descriptionAr')->with('descriptionAr')->first();
            return $text ? $text->descriptionAr->content : '';
        }else {
            $text = $this->getTexts()->whereHas('description')->with('description')->first();
            return $text ? $text->description->content : '';
        }
    }

    public function brand()
    {
        return $this->hasOne('App\ProductProperty', 'parentid', 'id')
            ->where([['type', 'Manufactured By'], ['langid', 'en']]);
    }

    public function brandAr()
    {
        return $this->hasOne('App\ProductProperty', 'parentid', 'id')
            ->where([['type', 'صُنع بواسطة'], ['langid', 'ar']]);
    }

    public function name($lang = null)
    {
        if($lang && $lang == 'ar'){
            $text = $this->getTexts()->whereHas('nameAr')->with('nameAr')->first();
            return $text ? $text->nameAr->content : '';
        }else {
            $text = $this->getTexts()->whereHas('name')->with('name')->first();
            return $text ? $text->name->content : '';
        }
    }


    public function color()
    {
        $text = $this->getAttr()->whereHas('color')->with('color')->first();
        return $text ? $text->color->content : '';
    }

    public function getAttr()
    {
        return ProductList::where([['parentid', $this->id], ['domain', 'attribute']]);
    }

    public function getTexts()
    {
       return ProductList::where([['parentid', $this->id], ['domain', 'text']]);
    }

    public function getMediaLink()
    {
        $prod = ProductList::where([['parentid', $this->id], ['domain', 'media']])->with('media')->first();
        return $prod ? $prod->media->link : null;
    }
}
