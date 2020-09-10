<?php

namespace Aimeos\Client\Html\Checkout\Confirm;

use App\Order;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class EbdaaClientStandard extends Standard
{
    /**
     *
     * Override parent method for send notification
     * @param \Aimeos\MW\View\Iface $view
     * @param array $tags
     * @param string|null $expire
     * @return \Aimeos\MW\View\Iface
     */
    public function addData(\Aimeos\MW\View\Iface $view, array &$tags = [], string &$expire = null): \Aimeos\MW\View\Iface
    {
        //notify vendors when order is created
        $data = parent::addData($view, $tags, $expire);

        $context = $this->getContext();
        if (($id = $context->getSession()->get('aimeos/orderid')) != null) {
            $new_order = session('order_' . $id);

            if (env('APP_ENV') == 'production') {
                $this->addQueueForVendorNotification($id, $new_order, $context);
            }

            if(env('APP_ENV') == 'development'){
                $this->addQueueForCustomerNotification($id, $new_order, $context);
            }

        }

        return $data;
    }


    public function addQueueForVendorNotification($id, $new_order, $context)
    {
        //get message for order
        try {
            //if message not created before then add queue
            if ($new_order == null) {
                session(['order_' . $id => 'vendor notifications sent']);

                $site_name = $context->getLocale()->getSiteItem()->getCode();
                $order_base_id = Order::where('id', $id)->first()->baseid;
                $data = [
                    'site_name' => $site_name,
                    'order_base_id' => $order_base_id,
                    'user_email' => $context->getLocale()->getSiteItem()->getConfigValue('emails'),
                    'user_mobile' => $context->getLocale()->getSiteItem()->getConfigValue('mobile')
                ];

                $queue = $this->getContext()->getMessageQueue('mq-email', 'order/email/vendor');
                $queue->add(json_encode([$data]));

                Log::channel('vendor_notification_success')->info('add queue for order ' . $id);

            }
        } catch (\Exception $e) {
            Log::channel('vendor_notification_failed')->info($e->getMessage());
        }

    }


    public function addQueueForCustomerNotification($id, $new_order, $context)
    {

        try {
            if ($new_order == null) {
            session(['order_' . $id => 'vendor notifications sent']);
                //get order base id
                $order_manager = \Aimeos\MShop::create($context, 'order');
                $base_id = $order_manager->getItem($id)->get('order.baseid');

                //get customer id
                $order_base_manager = \Aimeos\MShop::create($context, 'order/base');
                $customer_id = $order_base_manager->getItem($base_id)->get('order.base.customerid');

                //get customer mobile
                $customer_manager = \Aimeos\MShop::create($context, 'customer');
                $customer_mobile = $customer_manager->getItem($customer_id)->get('customer.mobile');

                if ($customer_mobile) {

                    $lang = app()->getLocale();

                    $data = [
                        'customer_mobile' => $customer_mobile,
                        'lang' => $lang
                    ];

                    //add queue
                    $queue = $this->getContext()->getMessageQueue('mq-sms', 'order/sms/customer');
                    $queue->add(json_encode([$data]));

                    Log::channel('customer_notification_success')->info('add queue for customer - ' . $customer_mobile);

                }

            }
        } catch (\Exception $e) {
            Log::channel('customer_notification_failed')->info($e->getMessage());
        }

    }
}
