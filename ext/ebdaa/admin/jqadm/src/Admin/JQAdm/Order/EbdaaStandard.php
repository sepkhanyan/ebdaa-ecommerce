<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2020
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Order;

use Aimeos\Admin\JQAdm\Order\Standard as CoreStandard;
use App\OrderBase;
use App\OrderBaseProduct;

sprintf( 'order' ); // for translation


/**
 * Default implementation of order JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class EbdaaStandard extends CoreStandard
{

	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Order\Item\Base\Iface $item Order base item object
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Order\Item\Base\Iface $item, bool $copy = false ) : array
	{
		$siteId = $this->getContext()->getLocale()->getSiteId();
		$data = $item->toArray( true );

		if( $item->getCustomerId() != '' )
		{
			$manager = \Aimeos\MShop::create( $this->getContext(), 'customer' );
			try {
                $customer_mobile = $manager->getItem( $item->getCustomerId() )->get('customer.mobile');
                $data += $manager->getItem( $item->getCustomerId() )->toArray();
                $data['customer.mobile'] = $customer_mobile;
			} catch( \Exception $e ) {};
		}


		if( $copy === true )
		{
			$data['order.base.siteid'] = $siteId;
			$data['order.base.id'] = '';
		}

		foreach( $item->getAddresses() as $type => $addresses )
		{
			foreach( $addresses as $pos => $addrItem )
			{
				$list = $addrItem->toArray( true );

				foreach( $list as $key => $value ) {
					$data['address'][$type][$pos][$key] = $value;
				}

				if( $copy === true )
				{
					$data['address'][$type][$pos]['order.base.address.siteid'] = $siteId;
					$data['address'][$type][$pos]['order.base.address.id'] = '';
				}
			}
		}

		if( $copy !== true )
		{
			foreach( $item->getServices() as $type => $services )
			{
				foreach( $services as $serviceItem )
				{
					$serviceId = $serviceItem->getServiceId();

					foreach( $serviceItem->getAttributeItems() as $attrItem )
					{
						foreach( $attrItem->toArray( true ) as $key => $value ) {
							$data['service'][$type][$serviceId][$key][] = $value;
						}
					}
				}
			}
		}

		foreach( $item->getProducts() as $pos => $productItem ) {
			$data['product'][$pos]['order.base.product.status'] = $productItem->getStatus();
		}

		return $data;
	}

    /**
     * Saves the data
     *
     * @return string|null HTML output
     */
    public function save() : ?string
    {
        $view = $this->getView();

        $manager = \Aimeos\MShop::create( $this->getContext(), 'order/base' );
        $manager->begin();

        try
        {
            $item = $this->fromArray( $view->param( 'item', [] ) );

            //get old statuses
            $orderBaseId = $item->getId();
            $oldStatuses = [];
            $orderProducts = OrderBaseProduct::where('baseid',$orderBaseId)->get();
            if(count($orderProducts) > 0){
                foreach($orderProducts as $key => $product){
                    $oldStatuses[$key] =  $product->status;

                }
            }
            $view->item = $item->getId() ? $item : $manager->store( clone $item );
            $view->itemBody = '';

            foreach( $this->getSubClients() as $client ) {
                $view->itemBody .= $client->save();
            }

            $manager->store( clone $view->item );
            $manager->commit();

            //get site id
            $url = url()->current();
            $explodedUrl = explode('/', $url);
            $siteCode = $explodedUrl[4];
            $siteId = \DB::table('mshop_locale_site')
                ->select('siteid')
                ->where('code', $siteCode)
                ->first()->siteid;

            //check if canceled products in order to change total amount
            foreach($item->getProducts() as $k => $prod){
                $priceSum = $prod->getQuantity() * $prod->getPrice()->getValue();
                $costSum =  $prod->getQuantity() * $prod->getPrice()->getCosts();
                $baseOrder = OrderBase::find($prod->getBaseId());
                if($prod->getStatus() == 0 && $oldStatuses[$k] != 0){
                    $updatedPrice = $baseOrder->price - $priceSum;
                    $updatedCosts = $baseOrder->costs - $costSum;
                    $baseOrder->price = $updatedPrice;
                    $baseOrder->costs = $updatedCosts;
                    $baseOrder->save();
                } else if(($siteId == $prod->getSiteId()) && ($prod->getStatus() != 0 && $oldStatuses[$k] == 0)){
                    $updatedPrice = $baseOrder->price + $priceSum;
                    $updatedCosts = $baseOrder->costs + $costSum;
                    $baseOrder->price = $updatedPrice;
                    $baseOrder->costs = $updatedCosts;
                    $baseOrder->save();
                }
            }

            return $this->redirect( 'order', $view->param( 'next' ), $view->item->getId(), 'save' );
        }
        catch( \Exception $e )
        {
            $manager->rollback();
            $this->report( $e, 'save' );
        }

        return $this->create();
    }
}
