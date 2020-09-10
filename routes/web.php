<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

use Illuminate\Http\Request;
Route::get('session', function(Request $request){
	return ['session' => $request->header('cookie')];
})->middleware('web');


Route::group(['prefix' => 'v1'], function () {
	Route::post('login', 'Api\AuthController@login');
	Route::post('register', 'Api\AuthController@register');
	Route::post('getUser', 'Api\AuthController@getUser');
	Route::get('logout', function(){
		Auth::logout();
		return ['success' => 1];
	});
});

Route::get('check-user-session', function(){
	if (Auth::check()) {
		return ['session' => 1];
	}
	return ['session' => 0];
});



Route::get('order', function(Request $request){
	$number = '9818593485';
	$pwd = 'India@123';
	$session  = $request->header('cookie');
	Auth::attempt(['mobile' => $number, 'password' => $pwd]);
	// dd($attempt);
	$client = new \GuzzleHttp\Client();
	$base = "http://ebdaa-ecommerce-beta.mzadqatar.com";
	$response = $client->request('GET', $base.'/default/jsonapi/order',['query' => ['laravel_session' => $session]]);
	$content = $response->getBody()->getContents();
	echo "<pre>";
	print_r($content);
});

Route::get('/', function(Request $request){
    if(!$request->has('productId')){
        return redirect('https://syaanh.page.link/download');
	}
	if($request->has('productId')){
		$productId = $request->get('productId');
		return view('aimeos-ext.deep', compact('productId'));
	}
});

Route::get('xasy679834asd-invoice/{id}/{siteId}', 'OrderInvoiceController@getOrder');
Route::get('asxcsv2547/inf', function(){
	return phpinfo();
});

//csv routes
Route::post('/product/validate/csv','Aimeos\jqadm\ProductController@validateCsv');
Route::post('/product/import-csv','Aimeos\jqadm\ProductController@importCsv');
Route::get('/product/media-scale/{site_name}','Aimeos\jqadm\ProductController@mediaScale');

//
Route::match(array('PATCH'), "/api/payments/{order_id}", array(
    'uses' => 'Aimeos\jsonapi\PaymentController@updateStatus',
));

//override Aimeos Checkout Controller
if (($conf = config('shop.routes.default', ['prefix' => 'shop'])) !== false) {
    Route::group($conf, function () {
        Route::match(array('GET', 'POST'), 'confirm/{code?}', array(
            'as' => 'aimeos_shop_confirm',
            'uses' => 'Aimeos\jsonapi\CheckoutController@confirmAction'
        ))->where(['site' => '[a-z0-9\.\-]+']);
    });
}

//get products stock
Route::post('/products/stock/','Aimeos\jsonapi\CheckoutController@getProductsStock');

//vendor orders
Route::get('/vendor/orders/','Aimeos\jqadm\OrderController@getVendorOrders');

//get received orders by excel
Route::get('/orders/vendor/excel','Aimeos\jqadm\OrderController@getVendorOrdersExcel')->name('received_orders_excel');

//get all orders orders by excel
Route::get('/orders/excel','Aimeos\jqadm\OrderController@getAllOrdersExcel')->name('all_orders_excel');

//products online csv
Route::get('/products/online-csv','Aimeos\jqadm\ProductController@getAllProductsOnline')->name('allProductsOnline');

//products online csv fblanguages
Route::get('/products/fblanguages','Aimeos\jqadm\ProductController@getAllProductsOnlineFbLanguages')->name('allProductsOnlineFbLanguages');

//products online csv google
Route::get('/products/googlecsv','Aimeos\jqadm\ProductController@getAllProductsOnlineGoogle')->name('allProductsOnlineGoogle');

// job commands
Route::get('/index/rebuild','Aimeos\jobs\JobsController@IndexRebuild')->name('index_rebuild');


// get chart data
Route::get('/sales/monthly','Aimeos\jsonapi\ChartController@getMonthlyOrders');


Route::group(['middleware' => ['auth']], function () {
	Route::get('sort/catalog', 'SortCatalogController@showCatalog')->name('sortCatalog');
	Route::get('sort/catalog/{id}', 'SortCatalogController@showCatalogProd')->name('catalogProdView');
	Route::post('sort/change', 'SortCatalogController@saveSort')->name('changePos');

});
