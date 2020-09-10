<?php

namespace Aimeos\Admin\JQAdm\Order\Invoice;


class EbdaaInvoiceStandard extends Standard
{
    public function fromArray(\Aimeos\MShop\Order\Item\Base\Iface $order, array $data)
    {
        $invoiceIds = $this->getValue( $data, 'order.id', [] );
        $manager = \Aimeos\MShop::create( $this->getContext(), 'order' );

        $search = $manager->createSearch()->setSlice( 0, count( $invoiceIds ) );
        $search->setConditions( $search->compare( '==', 'order.id', $invoiceIds ) );

        $items = $manager->searchItems( $search );


        foreach( $invoiceIds as $idx => $id )
        {


            if( !isset( $items[$id] ) ) {
                $item = $manager->createItem();
            } else {
                $item = $items[$id];
            }
            $order_service_item = $order->getService('delivery');
            if(count($order_service_item) > 0){
                $order_service_manager = \Aimeos\MShop::create( $this->getContext(), 'order/base/service' );
                $order_service_item = array_shift($order_service_item);
                // update final_costs
                if( isset( $data['order.base.service.final.costs'][$idx] ) ) {
                    $order_service_item->setServiceFinalCosts( $data['order.base.service.final.costs'][$idx] );
                    $order_service_manager->saveItem( $order_service_item);
                }
                // update delivery_type
                if( isset( $data['order.base.service.delivery.type'][$idx] ) ) {
                    $order_service_item->setDeliveryType( $data['order.base.service.delivery.type'][$idx] );
                    $order_service_manager->saveItem( $order_service_item);
                }
            }

            if( isset( $data['order.statusdelivery'][$idx] ) ) {
                $item->setDeliveryStatus( $data['order.statusdelivery'][$idx] );
            }

            if( isset( $data['order.statuspayment'][$idx] ) ) {
                $item->setPaymentStatus( $data['order.statuspayment'][$idx] );
            }

            if( isset( $data['order.datedelivery'][$idx] ) ) {
                $item->setDateDelivery( $data['order.datedelivery'][$idx] );
            }

            if( isset( $data['order.datepayment'][$idx] ) ) {
                $item->setDatePayment( $data['order.datepayment'][$idx] );
            }

            if( isset( $data['order.relatedid'][$idx] ) ) {
                $item->setRelatedId( $data['order.relatedid'][$idx] );
            }

            if( isset( $data['order.type'][$idx] ) ) {
                $item->setType( $data['order.type'][$idx] );
            }


            $item->setBaseId( $order->getId() );

            $manager->saveItem( $item );


        }
    }
}
