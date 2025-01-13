<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.adminpanel')]
#[Title('Orders')]

class Orders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = ''; // For search functionality
    public $selectedOrders = []; // Array for selected order IDs
    public $selectAll = false; // For the "Select All" checkbox
    public $orderIdToDelete = null; // For single delete
    public $showDeleteModal = false; // Control single delete modal visibility
    public $showMultipleDeleteModal = false; // Control multiple delete modal visibility

    protected $listeners = ['refreshComponent' => '$refresh'];

    // Confirm deletion for a single order
    public function confirmDelete($id)
    {
        $this->orderIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteOrder()
    {
        if ($this->orderIdToDelete) {
            $order = Order::findOrFail($this->orderIdToDelete);

            // Perform soft delete
            $order->items->each(function ($item) {
                // Soft delete related item attributes
                $item->itemAttributes()->delete();
                // Soft delete the order item
                $item->delete();
            });

            $order->delete(); // Soft delete the order

            session()->flash('message', 'Order deleted successfully.');
            $this->resetPage();
        }

        $this->orderIdToDelete = null;
        $this->showDeleteModal = false;
    }


    // Delete a single order
// public function deleteOrder()
// {
//     if ($this->orderIdToDelete) {
//         $order = Order::findOrFail($this->orderIdToDelete);

//         // Delete related order items and their attributes
//         $order->items->each(function ($item) {
//             // Delete related attributes
//             $item->itemAttributes()->delete();
//             // Delete the order item
//             $item->delete();
//         });

//         // Delete the order
//         $order->delete();

//         session()->flash('message', 'Order deleted successfully.');
//         $this->resetPage();
//     }

//     $this->orderIdToDelete = null;
//     $this->showDeleteModal = false;
// }


    // // Delete a single order
    // public function deleteOrder()
    // {
    //     if ($this->orderIdToDelete) {
    //         $order = Order::findOrFail($this->orderIdToDelete);

    //         // Delete the order
    //         $order->delete();

    //         session()->flash('message', 'Order deleted successfully.');
    //         $this->resetPage();
    //     }

    //     $this->orderIdToDelete = null;
    //     $this->showDeleteModal = false;
    // }

    // Toggle "Select All" checkbox
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedOrders = Order::pluck('id')->toArray();
        } else {
            $this->selectedOrders = [];
        }
    }

    // Handle individual checkbox updates
    public function updatedSelectedOrders()
    {
        $this->selectAll = count($this->selectedOrders) === Order::count();
    }

    // Confirm multiple deletions
    public function confirmMultipleDelete()
    {
        if (count($this->selectedOrders)) {
            $this->showMultipleDeleteModal = true;
        }
    }

    // Delete multiple selected orders
    public function deleteSelectedOrders()
{
    if (count($this->selectedOrders)) {
        // Retrieve selected orders with related items and attributes
        $orders = Order::whereIn('id', $this->selectedOrders)->get();

        foreach ($orders as $order) {
            // Soft delete order items and their attributes
            $order->items->each(function ($item) {
                $item->itemAttributes()->delete(); // Soft delete related attributes
                $item->delete(); // Soft delete the order item
            });

            // Soft delete the order
            $order->delete();
        }

        session()->flash('message', 'Selected orders deleted successfully.');
        $this->resetPage();
    }

    $this->selectedOrders = [];
    $this->showMultipleDeleteModal = false;
}


    public function render()
    {
        $orders = Order::with('user') // Include the customer relationship
            ->where(function ($query) {
                $query->whereHas('user', function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('payment_method', 'like', '%' . $this->search . '%') // Allow searching by payment method
                ->orWhere('payment_status', 'like', '%' . $this->search . '%') // Allow searching by payment status
                ->orWhere('status', 'like', '%' . $this->search . '%'); // Allow searching by order status
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.admin.orders', [
            'orders' => $orders,
            'totalOrdersCount' => $orders->total(),
        ]);
    }

}
