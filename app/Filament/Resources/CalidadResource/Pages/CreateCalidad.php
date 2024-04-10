<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use App\Models\Calidad;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;
use App\Enums\Calidad\BienvenidaEnum;

class CreateCalidad extends CreateRecord
{
    protected static string $resource = CalidadResource::class;

    protected static ?string $title = 'Auditoria Calidad';

    protected ?string $heading = 'Auditoria Calidad';

    // mutateFormDataBeforeCreate for user_id and call duration by subtracting the start and end time of the call using carbon

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Asign Logged in user to user_id in order to record who did the audit.
        $data['user_id'] = auth()->id();

        // Start with a total score of 100
        $totalScore = 100;

        // Define the deduction values for each possible response
        $bienvenidaDeductions = BienvenidaEnum::toValues();

        // Assuming the checkbox responses are in the $data array
        $bienvenidaResponses = $data['bienvenida'];

        // Deduct the values for the 'bienvenida' responses
        foreach ($bienvenidaResponses as $response) {
        // Deduct the value for this response from the total score
            if (isset($bienvenidaDeductions[$response])) {
                $totalScore -= $bienvenidaDeductions[$response];
            }

        // After mutating the data, return it
        return $data;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
}
