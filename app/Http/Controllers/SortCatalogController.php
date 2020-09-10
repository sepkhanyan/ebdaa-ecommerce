<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Catalog;
use App\Product;
use App\CatalogList;

class SortCatalogController extends Controller
{
    public function showCatalog($value='')
    {	
    	$catalogs = Catalog::select('id', 'label', 'url', 'status')
    	->where('status', '1')
    	->get();

    	return view('aimeos-ext.sortView', compact('catalogs'));
    }

    public function showCatalogProd($id)
    {
    	$prods = CatalogList::where('parentid', $id)
    	->where('siteid', '!=', '1.')
        ->orderBy('pos')
    	->get();
    	
   		return view('aimeos-ext.sortCatalogProdView', compact('prods'));
    }

    public function saveSort(Request $request)
    {	
    	$data = CatalogList::whereIn('refid', $request->get('prod'))
    	->whereIn('siteid', $request->get('site'))
    	->where('parentid', $request->get('category'))
        ->orderBy('pos')
    	->get();
        
    	foreach ($data as $key => $value) {
            $value->pos = $request->get('pos')[$key];
    		$value->save();
    	}
        
    	return back();
	}
	
	public function searchByProductId(Request $request)
    {
        if($request->has('id')){
            $prods = Product::getProductById($request->get('id'));
            return view('aimeos-ext.sortCatalogProdView', compact('prods'));
        }
        return 'No Product Found';
    }
}
