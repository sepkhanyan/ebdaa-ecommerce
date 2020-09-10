<?php

namespace Aimeos\Client\JsonApi\Basket\Product;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EbdaaBasketProductStandard extends Standard
{
    private $controller;

    /**
     * Initializes the client
     *
     * @param \Aimeos\MShop\Context\Item\Iface $context MShop context object
     * @param string $path Name of the client, e.g "basket"
     */
    public function __construct( \Aimeos\MShop\Context\Item\Iface $context, string $path )
    {
        parent::__construct( $context, $path );

        $this->controller = \Aimeos\Controller\Frontend\Basket\Factory::create( $this->getContext() );
    }

    private $post_request;
    /**
     * Override to get post request
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function post( ServerRequestInterface $request, ResponseInterface $response ) : \Psr\Http\Message\ResponseInterface
    {
        $this->post_request = $request;
        $body = (string) $request->getBody();
        $payload = json_decode( $body );
        foreach( $payload->data as $entry )
        {
            $qty = ( isset( $entry->attributes->quantity ) ? $entry->attributes->quantity : 1 );
            \DB::table('mshop_product')->where('id', $entry->attributes->{'product.id'})->increment('basket_add_count', $qty);
        }

        return parent::post($request, $response);
    }

}
