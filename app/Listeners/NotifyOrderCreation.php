<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Admin;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class NotifyOrderCreation
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Notify Admins
        $admins = Admin::all(); // Fetch all admins from the `admins` table
        //Log::info('Notifying Admins about Order', ['admins_count' => $admins->count()]);
        FacadesNotification::send($admins, new OrderCreatedNotification($order, 'admin'));

        // Notify Customer
        if ($order->user) { // Ensure the user relationship exists
            $order->user->notify(new OrderCreatedNotification($order, 'customer'));
        }

        // Optionally log the event
        // Log::info('Order Created Event Handled', [
        //     'order_id' => $order->id,
        //     'user_id' => $order->user_id,
        // ]);
    }
}
