<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.customerlogin')]

#[Title('Forgot Password')]

class ForgotPasswordPage extends Component
{

    public $email;

    public function sendResetLink(){
       $this->validate([
        'email'=>'required|email|exists:users,email|max:255'
       ]);

       $status = Password::sendResetLink(['email'=>$this->email]);

       if($status === Password::RESET_LINK_SENT){
         session()->flash('success','Password reset link has been sent to your email!');
         $this->email = '';
       }else{
        session()->flash('error','Something went wrong..!');
       }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
