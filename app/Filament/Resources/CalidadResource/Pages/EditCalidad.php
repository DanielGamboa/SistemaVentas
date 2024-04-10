<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
// List Enums
use App\Enums\Calidad\BienvenidaEnum;

class EditCalidad extends EditRecord
{
    protected static string $resource = CalidadResource::class;


    // protected function mutateFormDataBeforeFill(array $data): array
    // {
    //     $data['agente'] = auth()->id();
    
    //     return $data;
    // }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Asign Logged in user to user_id in order to record who did the audit.
        // $data['last_edited_by_id'] = auth()->id();

        // Start with a total score of 100
        // If evaluacion_completo it's true, then the total score is 100, otherwise it's 0
        $totalScore = $data['evaluacion_completa'] ? 100 : 0;
        // $totalScore = 100;

        // Define the deduction values for each possible response selected for the Calidad audit
        // Tab Preventa
        $bienvenidaDeductions = BienvenidaEnum::toValues();
        
        // Assuming the checkbox responses are in the $data array
        $bienvenidaResponses = $data['bienvenida'];

        // Deduct the values for the 'bienvenida' responses
        foreach ($bienvenidaResponses as $response) {
        // Deduct the value for this response from the total score
            if (isset($bienvenidaDeductions[$response])) {
                $totalScore -= $bienvenidaDeductions[$response];
                }
            }
        // If the total score is less than 0, set it to 0
        if ($totalScore < 0) {
            $totalScore = 0;
        }
        // After mutating the data, return it
        $data['calificacion'] = $totalScore;
            
                return $data;
    }

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
