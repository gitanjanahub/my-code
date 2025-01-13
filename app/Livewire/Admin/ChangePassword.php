<?php

namespace App\Livewire\Admin;

use App\Models\Admin;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]
#[Title('Change Password')]
class ChangePassword extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function updatePassword()
    {
        // Step 1: Validate Input
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|same:new_password_confirmation', // Ensure new password matches confirmation
            'new_password_confirmation' => 'required|string|min:6', // Confirm password
        ]);

        // Step 2: Check Current Password
        //$admin = Auth::guard('admin')->user();
        $admin = Auth::guard('admin')->user();
/** @var \App\Models\Admin $admin */ // Add this line to assist the IDE

        if (!Hash::check($this->current_password, $admin->password)) {
            session()->flash('error', 'Your current password does not match our records.');
            return;
        }

        // Step 3: Update Password
        $admin->password = Hash::make($this->new_password);
        $admin->save();

        // Optional: Log the action
        Log::info('Admin password updated for ID: ' . $admin->id);

        // Step 4: Logout Admin
        Auth::guard('admin')->logout();

        // Step 5: Redirect to Login Page
        session()->flash('success', 'Password changed successfully! Please log in again.');
        return redirect()->route('admin.login');
    }

    public function render()
    {
        return view('livewire.admin.change-password');
    }
}
