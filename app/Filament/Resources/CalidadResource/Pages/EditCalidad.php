<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;

class EditCalidad extends EditRecord
{
    protected static string $resource = CalidadResource::class;


    // protected function mutateFormDataBeforeFill(array $data): array
    // {
    //     $data['agente'] = auth()->id();
    
    //     return $data;
    // }

    protected function beforfill(): void
    {
        // Runs after the form fields are populated from the database.
        // Populate 'agente' from database by quereing the current calidad id and returning user_id from calidad table
        $currentAgent = $this->record->user_id;
        $agente = User::where('id', $currentAgent)->get()->pluck('name', 'id');
        $data['agente'] = $agente;
        // Remove the return statement from the void function
        
        // will this set the value of the 'agente' field in the form to the value of the $agente variable?
        $this->record->agente = $agente;
        // $this->record->agente = $agente;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
