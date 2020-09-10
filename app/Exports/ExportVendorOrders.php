<?php


namespace App\Exports;


use App\Order;
use App\OrderBase;
use App\OrderBaseProduct;
use App\Text;
use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Aimeos\MW\Translation\Gettext;

class ExportVendorOrders implements FromCollection, WithHeadings, WithMapping
{

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }


    public function collection()
    {

        $request = $this->request;
        $customer_site_id = User::where('id', $request['customer_id'])->first()->siteid;

        // get order id-s
        $order_base_product_baseids = OrderBaseProduct::where('siteid', $customer_site_id)->pluck('baseid')->toArray();


        $orders = OrderBase::whereIn('id', $order_base_product_baseids)
            ->with(['order', 'order_base_product', 'order_services', 'order_address']);

        $orders = $this->getFilters($request, $orders);

        return $orders->get();
    }

    public function headings(): array
    {
        return [
            'System item No#',
            'Order No#',
            'SKU/Model No#',
            'Item Name',
            'QTY',
            'Vendor',
            'Payment Collected By',
            'Order Status',
            'Inv. No.',
            'Amount QAR',
            'PROMO Price -Yes or No',
            'Fixing Comm Percentage',
            'Cost Price - Fixing',
            'Syaanh Comm',
            'Delivery Charge(QAR)',
            'PT Comm Percentage Retail',
            'PT Comm Percentage PROMO',
            'Syaanh Retail Comm',
            'Syaanh Promo Comm',
            'Cost Price - Planet Tec',
            'Delivery Type',
            'Delivery Final Costs',
            'Commission',
        ];
    }

    public function map($model): array
    {
        $order_delivery_service = $model->base->order_services->where('type','delivery')->first();
        $deliveryType = $order_delivery_service ? $order_delivery_service->delivery_type : '';
        $deliveryFinalCost = $order_delivery_service ? $order_delivery_service->final_costs : '';
        $order_product_price = $model->order_base_product->product_by_id->price();

        $product_name = $model->order_base_product->product_name();

        $deliveryStatusList = $this->getDeliveryStatuses();

        if (isset($model->order) && isset($model->order_base_product) && isset($model->order_services) && isset($model->order_address)) {
            $order_datails = [
                ' ',
                $model->id,
                $model->order_base_product->prodcode,
                $product_name,
                $model->order_base_product->quantity,
                $model->sitecode,
                ' ',
                $deliveryStatusList[$model->order->statusdelivery],
                $model->order->id,
                $model->price,
                ' ',
                ' ',
                $model->costs,
                ' ',
                $model->order_services->where('type', 'delivery')->first()->price,
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                $deliveryType,
                $deliveryFinalCost,
                $order_product_price->commission
            ];
            return $order_datails;
        }

        return [];

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

    public function getFilters($request, $orders)
    {

        //add filters

        if ($request['invoice'] !== null) {
            $orders = $orders->whereHas('order', function ($query) use ($request) {
                return $query->where('id', '=', $request['invoice']);
            });
        } elseif ($request['baseid'] !== null) {
            $orders = $orders->whereHas('order', function ($query) use ($request) {
                return $query->where('baseid', '=', $request['baseid']);
            });
        } elseif ($request['statuspayment'] !== null) {
            $orders = $orders->whereHas('order', function ($query) use ($request) {
                return $query->where('statuspayment', '=', $request['statuspayment']);
            });
        } elseif ($request['statusdelivery'] !== null) {
            $orders = $orders->whereHas('order', function ($query) use ($request) {
                return $query->where('statusdelivery', '=', $request['statusdelivery']);
            });
        } elseif ($request['cdate'] !== null && $request['end_date'] == null) {
            $orders = $orders->whereHas('order', function ($query) use ($request) {
                return $query->where('cdate', '=', $request['cdate']);
            });
        } elseif ($request['end_date'] !== null && $request['cdate'] == null) {
            $orders = $orders->whereHas('order', function ($query) use ($request) {
                return $query->where('cdate', '=', $request['end_date']);
            });
        } elseif ($request['sitecode'] !== null) {
            $orders = $orders->where('sitecode', $request['sitecode']);
        } elseif ($request['lastname'] !== null) {
            $orders = $orders->whereHas('order_address', function ($query) use ($request) {
                return $query->where('lastname', '=', $request['lastname']);
            });
        }
        if ($request['cdate'] !== null && $request['end_date'] !== null) {
            $orders = $orders->whereHas('order', function ($query) use ($request) {
                return $query->whereBetween('cdate', [$request['cdate'], $request['end_date']]);
            });
        }


        return $orders;
    }
}
