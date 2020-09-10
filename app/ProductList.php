<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductList extends Model
{
    protected $table = 'mshop_product_list';

    public $timestamps = false;

    public function description()
    {
        return $this->hasOne('App\Text',  'id', 'refid')->where([['type', 'short'], ['domain', 'product'], ['langid', 'en']]);
    }

    public function descriptionAr()
    {
        return $this->hasOne('App\Text',  'id', 'refid')->where([['type', 'short'], ['domain', 'product'], ['langid', 'ar']]);
    }

    public function name()
    {
        return $this->hasOne('App\Text',  'id', 'refid')->where([['type', 'name'], ['domain', 'product'], ['langid', 'en']]);
    }

    public function nameAr()
    {
        return $this->hasOne('App\Text',  'id', 'refid')->where([['type', 'name'], ['domain', 'product'], ['langid', 'ar']]);
    }

    public function color()
    {
        return $this->hasOne('App\Text',  'id', 'refid')->where([['type', 'name'], ['label', 'Color'], ['langid', 'en']]);
    }

    public function media()
    {
        return $this->hasOne('App\Media',  'id', 'refid');
    }



}
