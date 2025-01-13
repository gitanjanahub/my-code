<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]
#[Title('View Product')]

class OrderView extends Component
{

    public $order;

    public $orderToDelete = null;

    public $orderId; // Passed from the route
    protected $listeners = ['deleteOrderConfirmed']; // Listener for delete confirmation

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        //$this->order = Order::with(['user', 'items.product', 'items.itemAttributes.attributeName', 'items.itemAttributes.attributeValue'])
            //->findOrFail($this->orderId);

        $this->order = Order::with(['user', 'items.product'])
            ->findOrFail($this->orderId);
    }

    public function confirmDelete($id)
    {
        //$this->dispatchBrowserEvent('show-delete-confirmation-modal');
        $this->orderToDelete = $id;
    }



    public function deleteOrder()
    {
        if ($this->orderToDelete) {
            $order = Order::findOrFail($this->orderToDelete);

            // Perform soft delete
            $order->items->each(function ($item) {
                // Soft delete related item attributes
                $item->itemAttributes()->delete();
                // Soft delete the order item
                $item->delete();
            });

            $order->delete(); // Soft delete the order

            session()->flash('message', 'Order deleted successfully.');
            return redirect()->route('admin.orders'); // Redirect to orders list
        }

        session()->flash('error', 'Order could not be deleted.');
    }


    public function render()
    {
        return view('livewire.admin.order-view');
    }
}
