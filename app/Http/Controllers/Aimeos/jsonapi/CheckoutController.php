<?php

namespace App\Http\Controllers\Aimeos\jsonapi;

use Aimeos\Shop\Controller\CheckoutController as AimeosCheckoutController;
use App\Order;
use App\Product;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Log;
use Request;

class CheckoutController extends AimeosCheckoutController
{
    /**
     * Override this method for get response from json
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function confirmAction()
    {
        $order_id = Request::input('orderid');

        if ($order_id !== null && Auth::check()) {
            $order = Order::where('id', $order_id)->first();
            if (isset($order) && $order->type == 'jsonapi') {
                $transaction_id = Request::input('transaction_id');

                if ($order->statuspayment && $order->datepayment == null) {

                    $order->datepayment = Carbon::now();

                    if($transaction_id !== null){
                        $order->statuspayment = 5;
                    }
                    else{
                        $order->statuspayment = -1;
                    }

                    $order->save();


                    Log::channel('order_payment_success')->info('successfully updated order payment status for - ' . $order->id);
                    return response()->json(['message' => 'successfully updated','success' => 0]);
                } else {
                    return response()->json(['message' => 'updated before','success' => 1]);
                }

            } else {
                Log::channel('order_payment_failed')->info('order not found');
                return response()->json(['message' => 'order not found','success' => 1]);
            }
        }


        return parent::confirmAction();

    }

    public function getProductsStock(\Illuminate\Http\Request $request)
    {
        $ids = $request['ids'];
        $data = [];
        $products = Product::whereIn('id', $ids)->with('stock')->get();
        foreach($products as $product){
            $data [] = [
                'id' => $product['id'],
                'stock' => $product->stock['stocklevel'],
                'min_quantity' => $product->price()->quantity
            ];
        }
        return response()->json(['success' => 0, 'status_code' => 200, 'data' => $data]);
    }
}
