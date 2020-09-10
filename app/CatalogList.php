<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogList extends Model
{
    protected $table = 'mshop_catalog_list';
    public $timestamps = false;

    public function enableCatalog()
    {
        return $this->hasOne('App\Catalog', 'id', 'parentid')->where('status', 1);
    }
}
