<?php
namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('components.layouts.customerlogin')]

#[Title('Register Page')]

class RegisterPage extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    // Register user
    public function register()
    {
        // Validate input
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6|max:255',
        ]);

        // Save to database
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);

        // Login user
        auth()->login($user);

        // Transfer guest wishlist items to the newly registered user
        $guest_id = session()->get('guest_id');

        if ($guest_id) {
            // Get all wishlist items associated with the guest ID
            $guestWishlistItems = Wishlist::where('guest_id', $guest_id)->get();

            // Update the user_id for these items
            foreach ($guestWishlistItems as $item) {
                $item->update(['user_id' => $user->id, 'guest_id' => null]); // Remove guest_id and assign user_id
            }
        }

        // Clear the guest_id from the session
        session()->forget('guest_id');

        // Redirect to home
        return redirect()->intended();
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
