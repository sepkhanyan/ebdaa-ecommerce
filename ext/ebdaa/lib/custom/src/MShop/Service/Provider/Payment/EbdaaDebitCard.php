<?php


namespace Aimeos\MShop\Service\Provider\Payment;


class EbdaaDebitCard
    extends Base
    implements \Aimeos\MShop\Service\Provider\Payment\Iface
{
    public function process( \Aimeos\MShop\Order\Item\Iface $order, array $params = [] ) : ?\Aimeos\MShop\Common\Helper\Form\Iface
    {
        $basket = $this->getOrderBase( $order->getBaseId() );
        $total = $basket->getPrice()->getValue() + $basket->getPrice()->getCosts();

        // send the payment details to an external payment gateway
        $status = \Aimeos\MShop\Order\Item\Base::PAY_UNFINISHED;
        $order->setPaymentStatus( $status );
        $this->saveOrder( $order );
        // perform your actions
        return parent::process( $order, $params );
    }


}
