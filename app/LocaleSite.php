<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocaleSite extends Model
{
    protected $table = 'mshop_locale_site';

    public $timestamps = false;

    public static function getSiteNameFromId($id)
    {
    	return static::select('label')
    	->where('siteid', $id)
    	->first()->label;
    }

    public static function getSiteIdFromCode($name)
    {
		return static::select('siteid')
    	->where('code', $name)
    	->first()->siteid;
    }

	public static function getParentIdFromCode($code)
    {
    	return static::select('parentid')
    	->where('code', $code)
    	->first()->parentid;
    }
}
