<?php


namespace App\Exports;


use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportAllOrders implements FromCollection, WithHeadings, WithMapping
{

    private $request;


    /**
     * ExportAllOrders constructor.
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $collection = $this->getOrders();

        // push sum of Total amount column
        $order_total_amount_sum = $collection->sum(function ($item){
            return $item->base->price;
        });
        $collection->push($order_total_amount_sum);

        return $collection;
    }

    /**
     * @return array|string[]
     */
    public function headings(): array
    {
        return [
            'Order No#',
            'Order Date',
            'Vendor Name',
            'Product SKU',
            'Product Name',
            'QTY',
            'Total Amount',
            'Product Price',
            'Cost of Delivery',
            'Inv. No.',
            'Ship status',
            'Pay status',
            'Order comments',
            'Delivery Type',
            'Delivery Final Costs',
            'Commission',
            'Cost Price',
            'State'
        ];
    }

    /**
     * @param $item
     * @return array
     * @throws \Aimeos\MW\Translation\Exception
     */
    public function map($item): array
    {

        // if true, than item is a sum of a Total amount
        if(is_float($item) || is_int($item)){
            return [
                '',
                '',
                '',
                '',
                '',
                '',
                $item,
            ];
        }

        $order_details = [];

        // check if order have many products, push some order, but different products
        if (count($item->base_products) > 1) {
            foreach ($item->base_products as $key => $value) {
                array_push($order_details, $this->getOrderDetails($item, $key));
            }
        } else {
            $order_details = $this->getOrderDetails($item);
        }

        return $order_details;

    }

    /**
     * @return array
     * @throws \Aimeos\MW\Translation\Exception
     */
    public function getDeliveryStatuses()
    {

        $i18n_configs = [
            "mshop/code" => [
                base_path() . "/vendor/aimeos/aimeos-core/lib/mshoplib/i18n/code",
                base_path() . "/ext/ebdaa/lib/custom/i18n"
            ]
        ];

        $i18n = new \Aimeos\MW\Translation\Gettext($i18n_configs, app()->getLocale());

        return [
            '-1' => $i18n->dt('mshop/code', 'stat:-1'),
            '0' => $i18n->dt('mshop/code', 'stat:0'),
            '1' => $i18n->dt('mshop/code', 'stat:1'),
            '2' => $i18n->dt('mshop/code', 'stat:2'),
            '3' => $i18n->dt('mshop/code', 'stat:3'),
            '4' => $i18n->dt('mshop/code', 'stat:4'),
            '5' => $i18n->dt('mshop/code', 'stat:5'),
            '6' => $i18n->dt('mshop/code', 'stat:6'),
            '7' => $i18n->dt('mshop/code', 'stat:7'),
        ];
    }


    /**
     * @return array
     * @throws \Aimeos\MW\Translation\Exception
     */
    public function getPaymentStatuses()
    {

        $i18n_configs = [
            "mshop/code" => [
                base_path() . "/vendor/aimeos/aimeos-core/lib/mshoplib/i18n/code",
                base_path() . "/ext/ebdaa/lib/custom/i18n"
            ]
        ];

        $i18n = new \Aimeos\MW\Translation\Gettext($i18n_configs, app()->getLocale());

        return [
            '-1' => $i18n->dt('mshop/code', 'pay:-1'),
            '0' => $i18n->dt('mshop/code', 'pay:0'),
            '1' => $i18n->dt('mshop/code', 'pay:1'),
            '2' => $i18n->dt('mshop/code', 'pay:2'),
            '3' => $i18n->dt('mshop/code', 'pay:3'),
            '4' => $i18n->dt('mshop/code', 'pay:4'),
            '5' => $i18n->dt('mshop/code', 'pay:5'),
            '6' => $i18n->dt('mshop/code', 'pay:6'),
            '7' => $i18n->dt('mshop/code', 'pay:7'),
        ];


    }

    /**
     *
     * return array of order details
     * @param $model
     * @param int $index index for order products
     * @return array
     * @throws \Aimeos\MW\Translation\Exception
     */
    public function getOrderDetails($model, $index = 0)
    {
        $deliveryStatusList = $this->getDeliveryStatuses();
        $paymentStatusList = $this->getPaymentStatuses();

        //if order have many products put Total amount only for one
        $totalAmount = $model->base->price + $model->base->costs;
        $order_total_amount = $index == 0 ? $totalAmount : '';
        $commission = $model->base_products[$index]->product_by_id && $model->base_products[$index]->product_by_id->price() ? $model->base_products[$index]->product_by_id->price()->commission : '';
        $costPrice =  $model->base_products[$index]->product_by_id && $model->base_products[$index]->product_by_id->price() ? $model->base_products[$index]->product_by_id->price()->cost_price : '';

        $order_delivery_service = $model->base->order_services->where('type','delivery')->first();
        $deliveryType = $order_delivery_service ? $order_delivery_service->delivery_type : '';
        $deliveryFinalCost = $order_delivery_service ? $order_delivery_service->final_costs : '';

        // set delivery status and delivery price
        $deliveryStatus =  $deliveryStatusList[$model->base_products[$index]->status];
        $deliveryPrice = $deliveryStatus == 'Delivered' ? $model->base_products[$index]->price : 0;
        $deliveryCosts = $deliveryStatus == 'Delivered' ? $model->base_products[$index]->costs : 0;
        $state = $model->base_address->state;
        $order_datails = [
            $model->baseid,
            $model->cdate,
            $model->base_products[$index]->site->code,
            $model->base_products[$index]->prodcode,
            $model->base_products[$index]->name,
            intval($model->base_products[$index]->quantity),
            $order_total_amount,
            $deliveryPrice,
            $deliveryCosts,
            $model->id,
            $deliveryStatusList[$model->base_products[$index]->status],
            $paymentStatusList[$model->statuspayment],
            $model->base->comment,
            $deliveryType,
            $deliveryFinalCost,
            $commission,
            $costPrice,
            $state
        ];

        return $order_datails;
    }

    /**
     * @param $request
     * @param $orders
     * @return mixed
     */
    public function getFilters($request, $orders)
    {

        // get request keys and values
        $filter_val = $request->filter['val'];
        $filter_keys = $request->filter['key'];

        $order_base = '.base';
        $order_base_address = '.address';
        foreach ($filter_val as $key => $val) {
            if ($val != null) {
                // check if column from order_base_address table then search by that table
                if (strpos($filter_keys[$key], $order_base_address) !== false) {
                    // get column name
                    $column_name = substr($filter_keys[$key], 19);
                    $orders = $orders->whereHas('base_address', function ($query) use ($column_name, $val) {
                        return $query->where($column_name, '=', $val);
                    });
                    // check if column from order_base table then search by that table
                } else if (strpos($filter_keys[$key], $order_base) !== false) {
                    // get column name
                    $column_name = substr($filter_keys[$key], 11);
                    $orders = $orders->whereHas('base', function ($query) use ($column_name, $val) {
                        return $query->where($column_name, '=', $val);
                    });
                } else if (strpos($filter_keys[$key], 'order.') !== false) {
                    // get column name
                    $column_name = substr($filter_keys[$key], 6);

                    // check if need between dates or only by cdate
                     if ($key == '9') {
                        if((isset($filter_val['9']) && $filter_val['9'] != null) &&
                            (isset($filter_val['10']) && $filter_val['10'] != null) ){
                            $start =  $filter_val['9'];
                            $end =  Carbon::create($filter_val['10'])->subDay(1)->toDateString();
                            $orders = $orders->whereBetween('cdate', [$start, $end]);
                        }else{
                            $start = Carbon::create($val)->toDateTimeString();
                            $orders = $orders->where($column_name, '>=',$start);
                        }
                    }
                     // check if need ctime
                     elseif ($key == '10') {
                         if(!isset($filter_val['9']) && $filter_val['9'] == null){
                             $start = Carbon::create($val)->subDay(1)->toDateTimeString();
                             $orders = $orders->where($column_name, '<=',$start);
                         }
                     }
                    else{
                        $orders = $orders->where($column_name, $val);
                    }
                }
            }
        }
        return $orders;
    }

    public function getOrders(){
        $request = $this->request;

        // get site id for current site orders
        $site_id = DB::table('mshop_locale_site')
            ->where('code', '=', $request['site-code'])
            ->first('siteid')->siteid;

        $orders = Order::where('siteid', $site_id)
            ->with(['base', 'base_address']);

        $orders = $this->getFilters($request, $orders);

        return $orders->get();
    }

}
