<?php

namespace App\Livewire;

use Livewire\Component;

// Filament live wire requirements
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

// Form function properties
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;

// Call the model
use App\Models\User;

use Filament\Forms\Form;
use Illuminate\Contracts\View\View;


// Filament live wire requirements Implements HasForms
class DatabaseNotifications extends Component implements HasForms
{
    // Filament live wire requirements $data = []; can be called what ever you want but it will store form data.
    public ?array $data = [];

    // Filament live wire requirements - This will initialize the form.  Regardless of the form data state.
    public function mount(): void
    {
        $this->form->fill();
    }

    // Filament live wire requirements 
    use InteractsWithForms;
    // Filament live wire requirements this is a filament form function and schema declaration.
    // The ->statePath('data') is the data variable that will store the form data.
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required(),
                MarkdownEditor::make('content'),
                // ...
            ])
            ->statePath('data');
    }

    // Filament live wire requirements
    // This is the filament form function that will be called when the form is submitted.
    // Define a method to handle the form submission. In our example, we'll call this create(), but you can call it whatever you want.
    // Inside that method, you can validate and get the form's data using $this->form->getState(). It's important that you use this method 
    // instead of accessing the $this->data property directly, because the forms data needs to be validated and transformed into a useful 
    // format before being returned.

    // Filament live wire requirements CRUD
    // Live wire component test --> app/Livewire/DatabaseNotifications.php and resources/views/notifications/database_notifications-trigger.blade.php
    public function myfoo(): void
    {
        dd($this->form->getState());
        User::create($this->form->getState());
    }

    public function render()
    {
        return view('notifications.database-notifications-trigger.blade.php');
    }

    

}
