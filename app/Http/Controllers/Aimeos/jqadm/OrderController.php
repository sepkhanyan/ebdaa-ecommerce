<?php

namespace App\Http\Controllers\Aimeos\jqadm;

use App\Http\Controllers\Controller;
use App\Order;
use App\OrderBase;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderController extends Controller
{
    /**
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVendorOrders(Request $request)
    {


        $customer_site_id = User::where('id',$request['customer_id'])->first()->siteid;

        // get order id-s
        $order_base_product_baseids = DB::table('mshop_order_base_product')
            ->where('mshop_order_base_product.siteid', '=', $customer_site_id)
            ->pluck('baseid');

        //join and return orders data
        $orders = DB::table('mshop_order')
            ->whereIn('mshop_order.baseid',$order_base_product_baseids)
            ->join('mshop_order_base','mshop_order_base.id','=','mshop_order.baseid')
            ->join('mshop_order_base_address','mshop_order_base_address.baseid','=','mshop_order.baseid');

        $orders = $this->getFilters($request,$orders);

        $orders = $orders
            ->select('mshop_order.id as invoiceId', 'mshop_order.*', 'mshop_order_base.*', 'mshop_order_base_address.*')
            ->orderBy('mshop_order.id', 'desc')
            ->paginate(20);
        //select table columns and order by desc
        return response()->json(['success' => 1, 'data' => $orders]);
    }


    /**
     * @param $request
     * @param $orders
     * @return mixed
     */
    public function getFilters($request, $orders){

        //check and add new search params
        $request['invoice'] !== null ?  $orders = $orders->where('mshop_order.id', '=', $request['invoice']) : $orders;
        $request['baseid'] !== null ?  $orders = $orders->where('mshop_order.baseid', '=', $request['baseid']) : $orders;
        $request['statuspayment'] !== null ?  $orders = $orders->where('mshop_order.statuspayment', '=', $request['statuspayment']) : $orders;
        $request['statusdelivery'] !== null ?  $orders = $orders->where('mshop_order.statusdelivery', '=', $request['statusdelivery']) : $orders;
        $request['cdate'] !== null && $request['end_date'] == null ?  $orders = $orders->where('mshop_order.cdate', '=', $request['cdate']) : $orders;
        $request['end_date'] !== null && $request['cdate'] == null ?  $orders = $orders->where('mshop_order.cdate', '=', $request['end_date']) : $orders;
        $request['sitecode'] !== null ?  $orders = $orders->where('mshop_order_base.sitecode', '=', $request['sitecode']) : $orders;
        $request['lastname'] !== null ?  $orders = $orders->where('mshop_order_base_address.lastname', '=', $request['lastname']) : $orders;

        if($request['cdate'] !== null &&  $request['end_date'] !== null) {
            $orders = $orders->whereBetween('mshop_order.cdate', [$request['cdate'],$request['end_date']]);
        }

        return $orders;
    }



    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function getVendorOrdersExcel(Request $request){
        //download excel with vendor orders
        return Excel::download(new \App\Exports\ExportVendorOrders($request), 'orders.xlsx');
    }


    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function getAllOrdersExcel(Request $request){
        //download excel with all orders
        return Excel::download(new \App\Exports\ExportAllOrders($request), 'orders.xlsx');
    }


}
