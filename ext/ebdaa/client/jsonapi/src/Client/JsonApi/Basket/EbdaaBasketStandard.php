<?php

namespace Aimeos\Client\JsonApi\Basket;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EbdaaBasketStandard extends Standard
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
	
	/**
	 * Returns the resource or the resource list
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request Request object
	 * @param \Psr\Http\Message\ResponseInterface $response Response object
	 * @return \Psr\Http\Message\ResponseInterface Modified response object
	 */
	public function get( ServerRequestInterface $request, ResponseInterface $response ) : \Psr\Http\Message\ResponseInterface
	{	
		$view = $this->getView();

		$allow = false;
		$id = $view->param( 'id', 'default' );

		try
		{
			try
			{
				$view->item = $this->controller->load( $id, $this->getParts( $view ) );
			}
			catch( \Aimeos\MShop\Exception $e )
			{
				$view->item = $this->controller->setType( $id )->get();
				$allow = true;
			}

			$status = 200;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$status = 404;
			$view->errors = $this->getErrorDetails( $e, 'mshop' );
		}
		catch( \Exception $e )
		{
			$status = 500;
			$view->errors = $this->getErrorDetails( $e );
		}
		if(count($view->item->getProducts()) > 0){
			foreach( $view->item->getProducts() as $product ) {
    			$ids[] = $product->getProductId();		
    		}
    		$context = app( 'aimeos.context' )->get();
			$m = \Aimeos\MShop::create( $context, 'product' );
		
			$s = $m->createSearch()->setSlice( 0, count( $ids ) );
			$s->setConditions( $s->compare( '==', 'product.id', $ids ) );
			$products = $m->searchItems( $s, ['price'] );

			foreach( $view->item->getProducts() as $product ) {
			    if( isset( $products[$product->getProductId()] ) && !($prices = $products[$product->getProductId()]->getRefItems( 'price', 'default', 'default' ))->isEmpty() ) {
			        $product->minqty =
					$prices->first()->getQuantity();
			    }
			}	
		}
		
		return $this->render( $response, $view, $status, $allow );
	}
	
}