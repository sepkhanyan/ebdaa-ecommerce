<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2020
 * @package Controller
 * @subpackage Order
 */


namespace Aimeos\Controller\Jobs\Order\Email\Vendor;


use Aimeos\Client\Html\Exception;
use App\Notifications\OrderCreatedEmail;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Order payment e-mail job controller.
 *
 * @package Controller
 * @subpackage Order
 */
class Standard
	extends \Aimeos\Controller\Jobs\Base
	implements \Aimeos\Controller\Jobs\Iface
{
	/**
	 * Returns the localized name of the job.
	 *
	 * @return string Name of the job
	 */
	public function getName() : string
	{
		return $this->getContext()->getI18n()->dt( 'controller/jobs', 'Send email to vendor' );
	}


	/**
	 * Returns the localized description of the job.
	 *
	 * @return string Description of the job
	 */
	public function getDescription() : string
	{
		return $this->getContext()->getI18n()->dt( 'controller/jobs', 'Send email to vendor  when new order received with link in it' );
	}


	/**
	 * Executes the job.
	 *
	 * @throws \Aimeos\Controller\Jobs\Exception If an error occurs
	 */
	public function run()
	{
	    $context = $this->getContext();
        $queue = $context->getMessageQueue( 'mq-email', 'order/email/vendor' );
        $custManager = \Aimeos\MShop::create( $context, 'customer' );

        //after every $queue->get() method remove the queue
        while( ( $msg = $queue->get() ) !== null )
        {
            try
            {

                if( ( $list = json_decode( $msg->getBody(), true ) ) === null )
                {
                    $str = sprintf( 'Invalid JSON encode message: %1$s', $msg->getBody() );
                    throw new \Aimeos\Controller\Jobs\Exception( $str );
                }

                //get data
                $notification_data = json_decode( $msg->getBody(), true );

                //check cases

                if($notification_data[0]['user_email']){
                    //sent every vendor
                    foreach ($notification_data[0]['user_email'] as $email){
                        $this->sendEmailNotification($notification_data,$email);
                    }
                };

                if($notification_data[0]['user_mobile']){
                    $this->sendSMSNotification($notification_data);
                };
            }
            catch( \Exception $e )
            {
                Log::channel('vendor_notification_failed')->info($e->getMessage());
            }

            //remove queue
            $queue->del( $msg );
        }
	}

    public function sendEmailNotification($data,$email){
        $site_name = $data[0]['site_name'];
        $order_base_id = $data[0]['order_base_id'];

        try {
            Notification::route('mail',$email)->notify(new OrderCreatedEmail($site_name,$order_base_id));
            Log::channel('vendor_notification_success')->info('send mail for '.$order_base_id.'-'.$email);

        }catch (\Exception $e){
            Log::channel('vendor_notification_failed')->info($e->getMessage());
        }


    }

    public function sendSMSNotification($data){

        try {
            $mobile = $data[0]['user_mobile'];
            $text = 'New%20order:'.url('admin/'.$data[0]['site_name'].'/jqadm/get/order/'.$data[0]['order_base_id']);
            $url = "https://messaging.ooredoo.qa/bms/soap/Messenger.asmx/HTTP_SendSms?&customerID=2369&userName=mzadqtr&userPassword=8zY3Q41L&originator=Syaanh&smsText=$text&recipientPhone=974$mobile&messageType=0&defDate=&blink=false&flash=false&Private=false";

            $response = file($url);

            Log::channel('vendor_notification_success')->info('send sms for '.$data[0]['order_base_id'],$response);
        }catch (\Exception $e){
            Log::channel('vendor_notification_failed')->info($e->getMessage());

        }
    }



}
