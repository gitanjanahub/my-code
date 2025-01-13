<?php

namespace App\Livewire\Admin;

use App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminlogin')]

#[Title('Admin LogIn')]

class Login extends Component
{

    public $email;
    public $password;

    public function save()
{
    // Step 1: Validate the input fields
    $this->validate([
        'email' => 'required|email|max:255|exists:admins,email', // Check existence in 'admins' table
        'password' => 'required|min:6|max:255',
    ]);

    // Step 2: Attempt authentication using the 'admin' guard
    if (!Auth::guard('admin')->attempt(['email' => $this->email, 'password' => $this->password])) {
        session()->flash('error', 'Invalid Credentials');
        return;
    }

    // Step 3: Get the authenticated user
    $user = Auth::guard('admin')->user();
    //dd($user);

    //Step 4: Check the role and redirect accordingly
    if ($user->role==1) {
        return redirect()->to('/admin/dashboard'); // Admin Dashboard
    } elseif ($user->role==2) {
        dd('2');
        //return redirect()->to('/admin/staff-dashboard'); // Staff Dashboard
    }

    // Step 5: Default redirect if no role matches
    return redirect()->intended('/admin'); // Default admin route if needed
}



    public function render()
    {
        return view('livewire.admin.login');
    }
}
