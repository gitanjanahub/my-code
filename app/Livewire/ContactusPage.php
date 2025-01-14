<?php

namespace App\Livewire;

use App\Models\ContactDetail;
use Livewire\Component;

class ContactusPage extends Component
{
    public $contactDetail;

    public function mount()
    {
        // Fetch the first company details from the database
        $this->contactDetail = ContactDetail::first();
    }

    public function render()
    {
        return view('livewire.contactus-page', [
            'contactDetail' => $this->contactDetail,
        ]);
    }
}
