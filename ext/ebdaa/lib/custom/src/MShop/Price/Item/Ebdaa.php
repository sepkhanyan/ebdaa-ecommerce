<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2020
 * @package MShop
 * @subpackage Price
 */


namespace Aimeos\MShop\Price\Item;


/**
 * Default implementation of a price object.
 *
 * @package MShop
 * @subpackage Price
 */
class Ebdaa extends Standard
{

    public function getCostPrice(){
        return $this->get( 'price.cost_price' );
    }

    public function getCommission(){
        return $this->get( 'price.commission' );
    }

    public function toArray(bool $private = false): array
    {
        $data =  parent::toArray($private);
        $data['price.cost_price'] = $this->getCostPrice();
        $data['price.commission'] = $this->getCommission();

        return $data;
    }

    public function setCostPrice( $price )
    {

        return $this->set('price.cost_price', $price);
    }

    public function setCommission( $price )
    {
        return $this->set('price.commission', $price);
    }

    public function fromArray(array &$list, bool $private = false): \Aimeos\MShop\Common\Item\Iface
    {
        $item = parent::fromArray( $list, $private );

        // set default values if keys not exist
        $item = $item->setCostPrice( 0.00 );
        $item = $item->setCommission( 0.00 );

        foreach( $list as $key => $value )
        {

            if($key == 'price.cost_price'){
                $item = $item->setCostPrice( $value );
            }
            else if ($key == 'price.commission'){
                $item = $item->setCommission( $value );
            }

        }

        return $item;
    }
}
