<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2020
 * @package Controller
 * @subpackage Order
 */


namespace Aimeos\Controller\Jobs\Order\SMS\Customer;


use Illuminate\Support\Facades\Log;

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
        return $this->getContext()->getI18n()->dt( 'controller/jobs', 'Send sms to customer when new order received with whatsapplink link in it' );
    }


    /**
     * Executes the job.
     *
     * @throws \Aimeos\Controller\Jobs\Exception If an error occurs
     */
    public function run()
    {
        $context = $this->getContext();
        $queue = $context->getMessageQueue( 'mq-sms', 'order/sms/customer' );

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

                $notification_data = json_decode( $msg->getBody(), true );

                $this->sendSMSNotification($notification_data);

            }
            catch( \Exception $e )
            {
                Log::channel('customer_notification_failed')->info($e->getMessage());
            }

            //remove queue
            $queue->del( $msg );
        }
    }



    public function sendSMSNotification($data){



        try {
            $mobile = $data[0]['customer_mobile'];
            $lang = $data[0]['lang'];

            $client = new \GuzzleHttp\Client;

            if($lang == 'en'){
                $text = 'Thank%20you,%20we%20have%20received%20your%20order.%20For%20any%20enquiries%20or%20changes,%20feel%20free%20to%20chat%20to%20us%20-%20https://wa.link/yzc7wd';
                $url = "https://messaging.ooredoo.qa/bms/soap/Messenger.asmx/HTTP_SendSms?&customerID=2369&userName=mzadqtr&userPassword=8zY3Q41L&originator=Syaanh&smsText=$text&recipientPhone=974$mobile&messageType=0&defDate=&blink=false&flash=false&Private=false";

            }
            else{
                $text = 'تم%20استلام%20طلبك%20بنجاح%20،%20للإستفسار%20او%20تغيير%20تواصل%20مع%20صيانة.كوم%20وأتس%20اب%20https://wa.link/yzc7wd';
                $url = "https://messaging.ooredoo.qa/bms/soap/Messenger.asmx/HTTP_SendSms?&customerID=2369&userName=mzadqtr&userPassword=8zY3Q41L&originator=Syaanh&smsText=$text&recipientPhone=974$mobile&messageType=ArabicWithLatinNumbers&defDate=&blink=false&flash=false&Private=false";

            }

            $res = $client->request('GET', $url);

            if($res->getStatusCode() == 200){
                Log::channel('customer_notification_success')->info('send sms to '.$mobile.' number');
            }
            else{
                Log::channel('customer_notification_failed')->info('number - '.$mobile.' failed');

            }
        }catch (\Exception $e){
            Log::channel('customer_notification_failed')->info($e->getMessage());

        }
    }


}
