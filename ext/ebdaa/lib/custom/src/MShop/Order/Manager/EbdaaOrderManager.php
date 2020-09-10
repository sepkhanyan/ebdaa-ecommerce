<?php

namespace Aimeos\MShop\Order\Manager;


class EbdaaOrderManager extends Standard
{
    public function createItemBase(array $values = [], \Aimeos\MShop\Order\Item\Base\Iface $baseItem = null): \Aimeos\MShop\Order\Item\Iface
    {
        return new \Aimeos\MShop\Order\Item\EbdaaOrderItem( $values, $baseItem );
    }
}
