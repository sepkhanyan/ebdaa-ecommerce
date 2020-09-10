<?php

namespace App\Http\Controllers;

use App\ProductProperty;
use Auth;
use PDF;
use Illuminate\Http\Request;

class OrderInvoiceController extends Controller
{
    public function getOrder($orderId, $siteId)
    {
        if(Auth::check()){
            $context = app( 'aimeos.context' )->get();
            $manager = \Aimeos\MShop::create( $context, 'order' );
            $search = $manager->createSearch();
            $search->setConditions( $search->compare( '==', 'order.base.id', $orderId ) );

            $domains = ['order/base', 'order/base/address', 'order/base/coupon',
            'order/base/product', 'order/base/service'];
            $items = $manager->searchItems( $search, $domains );
            $base = $items->first()->getBaseItem();
            $lang = $base->getLocale()->getLanguageId();
            $attr = $this->getAttributesForInvoice($base->getProducts()->toArray());
            $mobile = $this->getMobile($base->getAddresses());
            $paymentType = $this->getPaymentType($base->getServices()->get('payment'));
            $prods = $base->getProducts();
            $models = [];
            foreach($prods as $key =>  $prod){
                $model = $this->getModelProperty($prod, $base);
                if($model){
                    $models[$key] = $model;
                }
            }

            $checkParentSiteOrnot = $this->checkParentSiteOrnot($siteId);

            if($checkParentSiteOrnot){
                $totalCost = $this->getTotalCost($base);
                $subTotal = '';
                $total = '';
            }else{
                $pricing = $this->getProdPricing($prods, $siteId);
                $subTotal = $pricing['subtotal'];
                $totalCost = $pricing['costs'];
                $total = $pricing['total'];
            }

            $address = $base->getAddresses()['payment'][0];

            // return view('aimeos-ext.invoice', compact('prods', 'address', 'base', 'totalCost', 'siteId', 'subTotal', 'total', 'checkParentSiteOrnot', 'paymentType', 'mobile', 'attr'));
            $pdf = PDF::loadView('aimeos-ext.invoice', compact('prods', 'address', 'base', 'totalCost', 'siteId', 'subTotal', 'total', 'checkParentSiteOrnot', 'paymentType', 'mobile', 'attr', 'models', 'lang'));
            return $pdf->download('invoice.pdf');
        }
    	return 'Invalid';
    }


    public function getTotalCost($base)
    {
        $basePrice = 'order.base.price';
        $baseCost = 'order.base.costs';
        $price = $base->$basePrice;
        $costs = $base->$baseCost;
        return $price + $costs;
    }

    public function checkParentSiteOrnot($siteId)
    {
        $explodeSite = explode('.', $siteId);
        if(count($explodeSite) > 3){
            return false;
        }
        return true;
    }

    public function getProdTotalCost($prods, $siteId)
    {
        $totalCosts = [];
        foreach ($prods as $prod) {
            $prodSiteId = 'order.base.product.siteid';
            $prodPrice = 'order.base.product.price';
            $prodQty = 'order.base.product.quantity';
            $prodCost = 'order.base.product.costs';
            if ($prod->$prodSiteId == $siteId) {
                $totalCosts = $prod->$prodPrice + $prod->$prodCost;
            }
        }
        return $totalCosts;
    }

    public function getProdPricing($prods, $siteId)
    {
        $data = [];
        foreach ($prods as $key => $prod) {
            $prodSiteId = 'order.base.product.siteid';
            $prodPrice = 'order.base.product.price';
            $prodQty = 'order.base.product.quantity';
            $prodCost = 'order.base.product.costs';
            $prodStatus = 'order.base.product.status';
            if ($prod->$prodSiteId == $siteId) {
                $data['subtotal'][$key] = $prod->$prodStatus != 0 ? $prod->$prodPrice * $prod->$prodQty : 0;
                $data['costs'][$key] = $prod->$prodStatus != 0 ? $prod->$prodCost * $prod->$prodQty : 0;
                $data['total'][$key] = $prod->$prodStatus != 0 ? $prod->$prodPrice * $prod->$prodQty + $prod->$prodCost * $prod->$prodQty : 0;
            }
        }
        $data['subtotal'] = array_sum($data['subtotal']);
        $data['costs'] = array_sum($data['costs']);
        $data['total'] = array_sum($data['total']);

        return $data;
    }

    public function getPaymentType($payment)
    {
        if($payment){
            $data = [];
            $paymentServiceCode = 'order.base.service.name';
            foreach ($payment as $key => $item) {
                return $item->$paymentServiceCode;
            }
            return $data;
        }
    }

    public function getMobile($order)
    {
        $telephone = 'order.base.address.telephone';
        foreach ($order as $key => $value) {;
            return $value[0]->$telephone;
        }
    }


    public function getAttributesForInvoice($order)
    {
        $data = [];
        foreach ($order as $key => $value) {
            foreach ($value->getAttributeItems() as $key2 => $attr) {
                $code = 'order.base.product.attribute.code';
                $value = 'order.base.product.attribute.value';
                $data[$key]['code'] = $attr->$code;
                $data[$key]['value'] = $attr->$value;
            }
        }
        return $data;
    }

    public function getModelProperty($prod, $base)
    {
        $prodId = 'order.base.product.productid';
        $lang = $base->getLocale()->getLanguageId();
        $type = $lang == 'en' ? 'Model' : 'الموديل';
        $model = ProductProperty::where([['parentid', $prod->$prodId], ['type', $type]])->first();
        $data = $model ? $model->value : null;
        return $data;
    }
}
