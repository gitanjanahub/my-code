<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.adminpanel')]
#[Title('Users')]

class Users extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $userIdToDelete = null;

    public $search; // Add a public property for the search term

    public $selectedUsers = []; // Array to hold selected user IDs

    public $selectAll = false; // Property for "Select All" checkbox

    public $showDeleteModal = false; // Control visibility of the single delete modal

    public $showMultipleDeleteModal = false; // Control visibility of the multiple delete modal

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function confirmDelete($id)
    {

        // Set user ID for individual deletion and show modal
        $this->userIdToDelete = $id;
        $this->showDeleteModal = true;  // Show the individual delete modal
    }

    public function deleteUser()
    {
        if ($this->userIdToDelete) {
            $user = User::find($this->userIdToDelete);

            if ($user) {
                $user->delete(); // Use soft delete

                session()->flash('message', 'User deleted successfully!');

                $this->resetPage();
            } else {
                session()->flash('error', 'user not found!');
            }

            $this->userIdToDelete = null;  // Reset user ID after deletion
            $this->showDeleteModal = false;  // Hide the modal after deletion
        }
    }


    public function updatedSelectAll($value)
    {

        if ($value) {
            // When "Select All" is checked, select all user IDs
            $this->selectedUsers = User::pluck('id')->toArray();
        } else {
            // If "Select All" is unchecked, clear the selection
            $this->selectedUsers = [];
        }
    }

    public function updatedSelectedUsers($value)
    {
        // When individual checkboxes are clicked, this method ensures
        // "Select All" is checked only if all items are selected
        $this->selectAll = count($this->selectedUsers) === User::count();

    }

    public function confirmMultipleDelete()
    {

        // Show the multiple delete modal if any users are selected
        if (count($this->selectedUsers)) {
            $this->showMultipleDeleteModal = true;
        }
    }

    public function deleteSelectedUsers()
    {
        // Perform delete for all selected users
        User::whereIn('id', $this->selectedUsers)->delete();
        session()->flash('message', 'Selected users deleted successfully!');
        $this->selectedUsers = []; // Clear selected users after deletion
        $this->showMultipleDeleteModal = false;  // Hide the modal after deletion
    }



    public function render()
    {
        $query = User::query();

        // Only apply search if $this->search is not empty
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $query->orderBy('created_at', 'desc');

        $users = $query->paginate(5);

        return view('livewire.admin.users',[
            'users' => $users,
            'totalUsersCount' => $users->total(),
        ]);
    }
}
