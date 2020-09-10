<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2020
 * @package MShop
 * @subpackage Order
 */


namespace Aimeos\MShop\Order\Item\Base\Service;


/**
 * Default implementation for order item base service.
 *
 * @package MShop
 * @subpackage Order
 */
class EbdaaOrderItemBaseService extends Standard
{
    public function getFinalCosts(){
        return $this->get( 'order.base.service.final.costs');
    }


    public function setServiceFinalCosts($value){
        return $this->set( 'order.base.service.final.costs', $value );
    }

    public function getDeliveryType(){
        return $this->get( 'order.base.service.delivery.type');
    }


    public function setDeliveryType($value){
        return $this->set( 'order.base.service.delivery.type', $value );
    }
}
