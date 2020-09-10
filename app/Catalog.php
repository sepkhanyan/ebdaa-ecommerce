<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $table = 'mshop_catalog';


    public static function getCategoryNameFromId($id)
    {
    	$data = static::select('label')
    	->where('id', $id)
    	->first();

    	if ($data) {
    		return $data->label;
    	}
    	return 'No Name';
    }
}
