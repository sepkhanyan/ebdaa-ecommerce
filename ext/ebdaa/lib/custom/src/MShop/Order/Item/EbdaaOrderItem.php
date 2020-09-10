<?php

namespace Aimeos\MShop\Order\Item;


class EbdaaOrderItem extends Standard

{

    public function toArray(bool $private = false): array
    {
        $list = parent::toArray($private);

        // get order base service
        $context = \App::make('aimeos.context')->get(false);
        $manager = \Aimeos\MShop::create($context, 'order/base/service');
        $search = $manager->createSearch();

        $expr = [
            $search->compare( '==', 'order.base.service.baseid', [$this->getBaseId()] ),
            $search->compare( '==', 'order.base.service.type', 'delivery' ),

        ];
        $search->setConditions($search->combine('&&',$expr));

        foreach( $manager->searchItems( $search ) as $order_base_service ) {
            $list['order.base.service.name'] = $order_base_service->getName();
            $list['order.base.service.final.costs'] = $order_base_service->getFinalCosts();
            $list['order.base.service.delivery.type'] = $order_base_service->getDeliveryType();
        }

        $list['order.type'] = $this->getType();
        $list['order.statusdelivery'] = $this->getDeliveryStatus();
        $list['order.statuspayment'] = $this->getPaymentStatus();
        $list['order.datepayment'] = $this->getDatePayment();
        $list['order.datedelivery'] = $this->getDateDelivery();
        $list['order.relatedid'] = $this->getRelatedId();

        if ($private === true) {
            $list['order.baseid'] = $this->getBaseId();
        }
        return $list;
    }
}

