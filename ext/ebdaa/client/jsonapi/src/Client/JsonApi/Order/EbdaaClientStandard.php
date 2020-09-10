<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2020
 * @package Client
 * @subpackage JsonApi
 */


namespace Aimeos\Client\JsonApi\Order;


use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EbdaaClientStandard extends Standard
{


    private $post_request;

    /**
     * Override for send notifications
     * @param string $baseId
     * @return \Aimeos\MShop\Order\Item\Iface
     */
    public function createOrder(string $baseId): \Aimeos\MShop\Order\Item\Iface
    {
        $item = parent::createOrder($baseId);

        $this->updateSoldCount($baseId);
        $this->addQueueForVendorNotification($baseId);
        $this->addQueueForCustomerNotification($baseId);

        return $item;
    }

    public function updateSoldCount($baseId)
    {
        $orderProducts = \DB::table('mshop_order_base_product')->where('baseid', $baseId)->get();
        if(count($orderProducts) > 0){
            foreach($orderProducts as $orderProduct){
                \DB::table('mshop_product')
                    ->where('id', $orderProduct->prodid)
                    ->increment('sold_count', $orderProduct->quantity);
            }
        }
    }

    public function addQueueForVendorNotification($baseId)
    {
        $context = $this->getContext();

        if (config('app.env') == 'production') {
            try {
                $site_name = $context->getLocale()->getSiteItem()->getCode();

                $data = [
                    'site_name' => $site_name,
                    'order_base_id' => $baseId,
                    'user_email' => $context->getLocale()->getSiteItem()->getConfigValue('emails'),
                    'user_mobile' => $context->getLocale()->getSiteItem()->getConfigValue('mobile')
                ];

                $queue = $this->getContext()->getMessageQueue('mq-email', 'order/email/vendor');
                $queue->add(json_encode([$data]));

                Log::channel('vendor_notification_success')->info('add queue for order ' . $baseId);

            } catch (\Exception $e) {
                Log::channel('vendor_notification_failed')->info($e->getMessage());
            }

        }
    }

    public function addQueueForCustomerNotification($baseId)
    {
        if (config('app.env') == 'production') {
            try {

                $context = $this->getContext();

                //get customer id
                $order_base_manager = \Aimeos\MShop::create($context, 'order/base');
                $customer_id = $order_base_manager->getItem($baseId)->get('order.base.customerid');

                //get customer mobile
                $customer_manager = \Aimeos\MShop::create($context, 'customer');
                $customer_mobile = $customer_manager->getItem($customer_id)->get('customer.mobile');

                if ($customer_mobile) {

                    $lang = $this->post_request->getParsedBody()['locale'];

                    $data = [
                        'customer_mobile' => $customer_mobile,
                        'lang' => $lang
                    ];

                    //add queue
                    $queue = $this->getContext()->getMessageQueue('mq-sms', 'order/sms/customer');
                    $queue->add(json_encode([$data]));

                    Log::channel('customer_notification_success')->info('add queue for customer - ' . $customer_mobile);

                }
            } catch (\Exception $e) {
                Log::channel('customer_notification_failed')->info($e->getMessage());
            }
        }
    }

    /**
     * Override to get post request
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function post(ServerRequestInterface $request, ResponseInterface $response): \Psr\Http\Message\ResponseInterface
    {
        //get post request for language
        $this->post_request = $request;
        return parent::post($request, $response);
    }

}
