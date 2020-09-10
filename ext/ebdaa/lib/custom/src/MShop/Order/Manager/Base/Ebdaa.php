<?php

namespace Aimeos\MShop\Order\Manager\Base;

use App\OrderSession;

class Ebdaa extends Standard
{
	public function getSession (string $type = 'default') : \Aimeos\MShop\Order\Item\Base\Iface
    {
    	$context = $this->getContext();
        $session = $context->getSession();
        $locale = $context->getLocale();
        $currency = $locale->getCurrencyId();
        $language = $locale->getLanguageId();
        $sitecode = $locale->getSiteItem()->getCode();
        $userid = $this->getContext()->getUserId();
        $key = 'aimeos/basket/content-' . $sitecode . '-' . $language . '-' . $currency . '-' . strval( $type );
        if(empty($userid)){
            if( ( $serorder = $session->get( $key ) ) === null ) {
                return $this->getObject()->createItem();
            }    
        }else{
            $serorder = OrderSession::where('key', $key)
            ->where('user_id', $userid)->pluck('order')->toArray();
            if($serorder){
                $serorder = $serorder[0];
            }else{
                return $this->getObject()->createItem();    
            }
        }
        
        $iface = \Aimeos\MShop\Order\Item\Base\Iface::class;
        if( ( $order = unserialize( $serorder ) ) === false || !( $order instanceof $iface ) )
        {
            $msg = sprintf( 'Invalid serialized basket. "%1$s" returns "%2$s".', __METHOD__, $serorder );
            $context->getLogger()->log( $msg, \Aimeos\MW\Logger\Base::WARN );
            return $this->getObject()->createItem();
        }
        \Aimeos\MShop::create( $context, 'plugin' )->register( $order, 'order' );
        return $order;
    }	

    /**
	 * Saves the current shopping basket of the customer.
	 *
	 * @param \Aimeos\MShop\Order\Item\Base\Iface $order Shopping basket
	 * @param string $type Order type if a customer can have more than one order at once
	 * @return \Aimeos\MShop\Order\Manager\Base\Iface Manager object for chaining method calls
	 */
    public function setSession( \Aimeos\MShop\Order\Item\Base\Iface $order, string  $type = 'default') : \Aimeos\MShop\Order\Manager\Base\Iface
    {
    	$context = $this->getContext();

        $session = $context->getSession();
        $locale = $context->getLocale();
        $currency = $locale->getCurrencyId();
        $language = $locale->getLanguageId();
        $sitecode = $locale->getSiteItem()->getCode();
        $userid = $this->getContext()->getUserId();
        $key = 'aimeos/basket/content-' . $sitecode . '-' . $language . '-' . $currency . '-' . strval( $type );
        $list = $session->get( 'aimeos/basket/list', [] );
        $list[$key] = $key;
        $session->set( 'aimeos/basket/list', $list );
        if($userid){
            $ordersession = OrderSession::where('user_id', $userid)->first();

            if($ordersession){
                $ordersession->update([
                    'order' => serialize( clone $order ),
                    'key'   => $key
                ]);
            }else{
                OrderSession::create([
                    'order' => serialize( clone $order ),
                    'user_id' => $userid,
                    'key'     => $key,
                    'active'  => 1
                ]);
            }
        }else{
            $session->set( $key, serialize( clone $order ) );    
        }
        return $this;
    }	
}
