<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedEmail extends Notification
{
    use Queueable;

    public $site_name;
    public $order_base_id;

    /**
     * Create a new notification instance.
     *
     * @param $site_name
     * @param $order_base_id
     */
    public function __construct($site_name, $order_base_id)
    {
        $this->site_name = $site_name;
        $this->order_base_id = $order_base_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $url = url('admin/'.$this->site_name.'/jqadm/get/order/'.$this->order_base_id);

        return (new MailMessage)
            ->subject('New order')
            ->greeting('Hello from '.$this->site_name.' shop')
            ->line('You have new order')
            ->action('View order', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
