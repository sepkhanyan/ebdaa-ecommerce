<?php


namespace Aimeos\MShop\Customer\Item;


class Laravel extends \Aimeos\MShop\Customer\Item\Standard
{

    private $myvalues;

    public function __construct(\Aimeos\MShop\Common\Item\Address\Iface $address, array $values = [],
                                array $listItems = [], array $refItems = [], array $addrItems = [], array $propItems = [],
                                \Aimeos\MShop\Common\Helper\Password\Iface $helper = null, string $salt = null)
    {
        parent::__construct($address, $values, $listItems, $refItems, $addrItems, $propItems);
        $this->myvalues = $values;
    }


    public function getMobile(){
        return $this->get( 'customer.mobile', '' );
    }

    public function toArray(bool $private = false): array
    {
        $list = parent::toArray($private);

        $list['customer.mobile'] = $this->getMobile();

        return $list;
    }

}
