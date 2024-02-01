<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use App\Models\Calidad;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;

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
        // After mutating the data, return it
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
