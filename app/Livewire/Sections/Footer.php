<?php

namespace App\Livewire\Sections;

use App\Models\ContactDetail;
use Livewire\Component;

class Footer extends Component
{
    public $contactDetail;

    public function mount()
    {

        $this->contactDetail = ContactDetail::first();
    }

    public function render()
    {
        return view('livewire.sections.footer');
    }
}
