<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $role;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $role)
    {
        $this->order = $order;
        $this->role = $role;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
        //Log::info('Notification role', ['role' => $this->role]);
        $currencySymbol = $this->order->currency === 'inr' ? 'Rs' : '$';  // Example for USD and EUR currencies

        $mailMessage = (new MailMessage)
            ->subject('New Order Created')
            ->line('Order ID: ' . $this->order->id)
            ->line('Total: ' . number_format($this->order->grand_total, 2, '.', ',') . ' ' . $currencySymbol);

        if ($this->role === 'admin') {
            $mailMessage->line('Customer Name: ' . $this->order->user->name)
                        ->action('View Order', url('/admin/orders/' . $this->order->id));
        } else {
            $mailMessage->line('Thank you for your purchase!')
                        ->action('View Order', url('/orders/' . $this->order->id));
        }

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $data = [
            'order_id' => $this->order->id,
            'total' => $this->order->grand_total,
        ];

        if ($this->role === 'admin') {
            $data['customer_name'] = $this->order->user->name;
        }

        return $data;
    }
}
