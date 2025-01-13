<?php

namespace App\Livewire\Admin;

use App\Models\Attribute;
use App\Models\AttributeName;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]

#[Title('Create Attribute')]

class AttributeCreate extends Component
{

    public $attributeName;
    public $values = [];
    public $newValue;


    // Remove a value from the list
    public function removeValue($index)
    {
        unset($this->values[$index]);
        $this->values = array_values($this->values); // Reindex array
    }



    public function addValue()
    {
        // Ensure the value isn't empty or duplicated before adding it
        if ($this->newValue && !in_array($this->newValue, $this->values)) {
            $this->values[] = $this->newValue;
            $this->newValue = ''; // Reset the input field only after adding

            // Log the updated array to Laravel log
        // Log::info('Values array after adding new value:', $this->values);
        }
    }

    public function updatedNewValue($value)
    {
        // Check if the new value is not empty and not already in the list
        if ($value && !in_array($value, $this->values)) {
            $this->values[] = $value; // Add the value to the array
            $this->newValue = ''; // Clear the input field
        }

        // Log the updated array to see if it's being added correctly
        //Log::info('Updated Values array:', $this->values);
    }


    public function save()
    {
        // Validate the inputs before saving
        $this->validate([
            'attributeName' => 'required|string|max:255',
            'values' => 'required|array|min:1', // Ensure there is at least one value
            'values.*' => 'required|string|max:255', // Ensure each value is required and a string
        ]);

        // Check for duplicate values
        // $uniqueValues = array_unique($this->values);
        // if (count($this->values) !== count($uniqueValues)) {
        //     // Add a validation error manually for duplicates
        //     $this->addError('values', 'Duplicate values are not allowed.');
        //     return; // Stop execution if duplicates are found
        // }

        // Create a new attribute
        $attribute = AttributeName::create(['name' => $this->attributeName]);

        // Insert all unique values related to this attribute
        foreach ($this->values as $value) {
            AttributeValue::create([
                'attribute_name_id' => $attribute->id,
                'value' => $value,
            ]);
        }

        // Reset the form after saving
        $this->reset(['attributeName', 'values', 'newValue']);
        session()->flash('message', 'Attribute and values saved successfully.');
        return redirect()->route('admin.attributes');
    }



    public function render()
    {
        return view('livewire.admin.attribute-create');
    }
}
