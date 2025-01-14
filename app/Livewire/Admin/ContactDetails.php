<?php

namespace App\Livewire\Admin;

use App\Models\ContactDetail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('components.layouts.adminpanel')]
#[Title('Contact Us Details')]
class ContactDetails extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $logo;
    public $address;
    public $uploadedLogo; // Holds existing logo path for editing
    public $previewLogo;  // Temporary preview for new logo upload

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'logo' => 'nullable|image|max:2048', // Max 2MB for logo
        'address' => 'nullable|string',
    ];

    public function mount()
    {
        $contactDetail = ContactDetail::first();

        if ($contactDetail) {
            $this->name = $contactDetail->name;
            $this->email = $contactDetail->email;
            $this->phone = $contactDetail->phone;
            $this->uploadedLogo = $contactDetail->logo;
            $this->address = $contactDetail->address;
        }
    }

    public function updatedLogo()
    {
        // Generate a temporary URL for the uploaded file for live preview
        $this->previewLogo = $this->logo->temporaryUrl();
    }

    public function save()
    {
        $this->validate();

        $logoPath = $this->uploadedLogo;

        if ($this->logo) {
            $logoPath = $this->logo->store('images/logos', 'public'); // Save logo to storage/public/images/logos
        }

        $contactDetail = ContactDetail::first();

        if ($contactDetail) {
            $contactDetail->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'logo' => $logoPath,
                'address' => $this->address,
            ]);
            session()->flash('success', 'Contact details updated successfully!');
        } else {
            ContactDetail::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'logo' => $logoPath,
                'address' => $this->address,
            ]);
            session()->flash('success', 'Contact details added successfully!');
        }

        // Refresh uploaded logo
        $this->uploadedLogo = $logoPath;
        $this->previewLogo = null; // Clear temporary preview
    }

    public function render()
    {
        return view('livewire.admin.contact-details');
    }
}
