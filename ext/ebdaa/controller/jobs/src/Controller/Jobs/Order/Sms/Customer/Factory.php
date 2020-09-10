<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2020
 * @package Controller
 * @subpackage Order
 */


namespace Aimeos\Controller\Jobs\Order\Sms\Customer;


/**
 * Order Customer sms controller factory.
 *
 * @package Controller
 * @subpackage Order
 */
class Factory
    extends \Aimeos\Controller\Jobs\Common\Factory\Base
    implements \Aimeos\Controller\Jobs\Common\Factory\Iface
{
    /**
     * Creates a new controller specified by the given name.
     *
     * @param \Aimeos\MShop\Context\Item\Iface $context Context object required by controllers
     * @param \Aimeos\Bootstrap $aimeos \Aimeos\Bootstrap object
     * @param string|null $name Name of the controller or "Standard" if null
     * @return \Aimeos\Controller\Jobs\Iface New controller object
     */
    public static function create( \Aimeos\MShop\Context\Item\Iface $context, \Aimeos\Bootstrap $aimeos, string $name = null ) : \Aimeos\Controller\Jobs\Iface
    {

        if( $name === null ) {
            $name = $context->getConfig()->get( 'controller/jobs/order/sms/Customer/name', 'Standard' );
        }

        $iface = '\\Aimeos\\Controller\\Jobs\\Iface';
        $classname = '\\Aimeos\\Controller\\Jobs\\Order\\Sms\\Customer\\' . $name;

        if( ctype_alnum( $name ) === false ) {
            throw new \Aimeos\Controller\Jobs\Exception( sprintf( 'Invalid characters in class name "%1$s"', $classname ) );
        }

        $controller = self::createController( $context, $aimeos, $classname, $iface );


        return self::addControllerDecorators( $context, $aimeos, $controller, 'order/sms/customer' );
    }
}
