<?php

namespace App\Http\Controllers\Aimeos\jsonapi;

use App\Http\Controllers\Controller;
use App\LocaleSite;
use App\Order;
use App\OrderBase;
use App\OrderBaseProduct;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function getMonthlyOrders(Request $request)
    {
        $site = $request['site'];
        $site_id = LocaleSite::where('code', $site)->first()->siteid;

        $months = Order::select('cmonth')->distinct()->get()->toArray();

        $response = [];
        foreach ($months as $key => $val) {
            $base_ids = Order::where('cmonth', $val['cmonth'])
                ->where('statuspayment', '>=', 5)
                ->pluck('baseid');

            if ($site == 'syaanh') {
                $sum_of_monthly_price = OrderBase::whereIn('id', $base_ids)->sum('price');
            } else {

                $base_ids_from_base_products = OrderBaseProduct::whereIn('baseid', $base_ids)
                                                ->where('siteid', $site_id)
                                                ->pluck('baseid');
                $sum_of_monthly_price = OrderBase::whereIn('id', $base_ids_from_base_products)->sum('price');

            }

            $response[$val['cmonth']] = $sum_of_monthly_price;

        }

        return response()->json(['success' => 1, 'data' => $response]);

    }
}
