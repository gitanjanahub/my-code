<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.customerlogin')]

#[Title('LogIn')]

class LoginPage extends Component
{

    public $email;
    public $password;

    public function save(){
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:6|max:255'
        ]);

        if(!auth()->attempt(['email'=>$this->email, 'password'=>$this->password])){
            session()->flash('error','Invalid Credentials');
            return;
        }

        return redirect()->intended();
    }

    public function render()
    {
        return view('livewire.auth.login-page')->with('no_layout', true);
    }
}