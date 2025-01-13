<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My Profile Page')]

class MyProfile extends Component
{

    public $name;
    public $email;
    public $created_at;
    public $order_count;
    public $wishlist_count;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->created_at = $user->created_at->format('F d, Y');
        $this->order_count = Order::where('user_id', $user->id)->count();
        $this->wishlist_count = Wishlist::where('user_id', $user->id)->count();
    }

    public function render()
    {
        return view('livewire.my-profile');
    }
}
