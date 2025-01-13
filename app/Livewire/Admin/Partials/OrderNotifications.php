<?php

namespace App\Livewire\Admin\Partials;

use App\Models\Order;
use Livewire\Component;

class OrderNotifications extends Component
{

    public $newOrdersCount = 0;
    public $newOrders = [];

    protected $listeners = ['orderCreated' => 'updateNotifications'];

    public function mount()
    {
        $this->updateNotifications();
    }

    public function updateNotifications()
    {
        //$query = Order::where('created_at', '>=', now()->subDay());
        $query = Order::with('user')
                  ->where('status', 'new');

        $this->newOrders = $query->latest()->take(5)->get();
        $this->newOrdersCount = $query->count(); // Accurate count of new orders.
    }


    public function render()
    {
        return view('livewire.admin.partials.order-notifications');
    }
}
