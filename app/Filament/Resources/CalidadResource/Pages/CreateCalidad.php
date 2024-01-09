<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCalidad extends CreateRecord
{
    protected static string $resource = CalidadResource::class;

    protected static ?string $title = 'Auditoria Calidad';

    protected ?string $heading = 'Auditoria Calidad';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
