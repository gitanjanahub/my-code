<?php

namespace App\Livewire\Admin;
use App\Models\AttributeName;
use App\Models\AttributeValue;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]

#[Title('Edit Attribute')]

class AttributeEdit extends Component
{

    public $attributeId;
    public $attributeName;
    public $values = [];
    public $newValue;
    public $deletedValues = []; // Array to track deleted values

    public function mount($id)
    {
        // Load the attribute and its values
        $attribute = AttributeName::findOrFail($id);
        $this->attributeId = $attribute->id;
        $this->attributeName = $attribute->name;

        // Fetch existing values and store them with their IDs
        $this->values = AttributeValue::where('attribute_name_id', $this->attributeId)
                        ->pluck('value', 'id')
                        ->toArray();
    }

    // Add new value to the list
    // public function addValue()
    // {
    //     if ($this->newValue && !in_array($this->newValue, $this->values)) {
    //         $this->values['new_' . count($this->values)] = $this->newValue; // Use 'new_' prefix to identify new rows
    //         $this->newValue = ''; // Reset input field
    //     }
    // }

    // Remove a value from the list
    public function removeValue($key)
    {
        if (!str_starts_with($key, 'new_')) {
            // Track existing value for deletion
            $this->deletedValues[] = $key;
        }

        unset($this->values[$key]);
    }


    public function addValue()
{
    // Ensure new value is not empty and not already in the list of existing values
    if ($this->newValue && !in_array($this->newValue, $this->values)) {
        // Add new value to the values array with a unique key (new_)
        $this->values['new_' . count($this->values)] = $this->newValue;
        $this->newValue = ''; // Reset the input field
    }
}

public function updatedNewValue($value)
{
    // Ensure the new value is not empty and not already in the list
    if ($value && !in_array($value, $this->values) && !in_array($value, array_values($this->values))) {
        // Add the value to the list as a new entry
        $this->values['new_' . count($this->values)+1] = $value; // Use 'new_' to identify new values
        $this->newValue = ''; // Clear the input field
    }
}

public function save()
{
    //dd($this->values);
    // Validate the input fields
    $this->validate([
        'attributeName' => 'required|string|max:255',
        'values' => 'required|array|min:1',
        'values.*' => 'required|string|max:255',
    ]);

    // Update the attribute name
    $attribute = AttributeName::findOrFail($this->attributeId);
    $attribute->update(['name' => $this->attributeName]);

    // Process the values
    foreach ($this->values as $key => $value) {
        if (str_starts_with($key, 'new_')) {
            //dd($value);
            // Insert new value (ensure it is not marked for deletion)
            AttributeValue::create([
                'attribute_name_id' => $this->attributeId,
                'value' => $value,
                'deleted_at' => NULL, // Explicitly set deleted_at to NULL for new rows
            ]);
        } else {
            // Update existing value, but only update if it is an actual record and not a new one
            $existingValue = AttributeValue::find($key);
            if ($existingValue) {
                $existingValue->update(['value' => $value]);
            }
        }
    }

    // Remove values from the database that were deleted in the form
    $existingIds = array_filter(array_keys($this->values), function($key) {
        return !str_starts_with($key, 'new_');
    });

    // Delete values from the database that were removed in the form
    if (!empty($this->deletedValues)) {
        AttributeValue::whereIn('id', $this->deletedValues)->delete();
    }

    // AttributeValue::where('attribute_name_id', $this->attributeId)
    //     ->whereNotIn('id', $existingIds)
    //     ->delete(); // Soft deletes rows that are no longer included

    session()->flash('message', 'Attribute updated successfully.');
    return redirect()->route('admin.attributes');
}





    public function render()
    {
        return view('livewire.admin.attribute-edit');
    }
}
